<?php

namespace App\Services;

use App\Models\JobFeeRule;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Order;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * --------------------------------------------------------------------------
 * WalletService
 * --------------------------------------------------------------------------
 *
 * Handles all wallet-related financial operations including:
 * - Top-up (crediting balance)
 * - Job fee deduction (debiting balance)
 * - Worker wallet status validation
 *
 * All operations are executed inside database transactions to ensure
 * consistency and prevent race conditions.
 */
class WalletService
{
    /**
     * ----------------------------------------------------------------------
     * Top Up Wallet
     * ----------------------------------------------------------------------
     *
     * Adds balance to a worker wallet and logs the transaction.
     * Automatically activates the wallet if it is suspended.
     *
     * @param int $workerId
     * @param float $amount
     * @param int $adminId
     * @param string|null $note
     *
     * @return WalletTransaction
     */
    public function topUp(
        int $workerId,
        float $amount,
        int $adminId,
        string $note = null
    ) {

        return DB::transaction(function () use (
             $workerId,
            $amount,
            $adminId,
            $note
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Wallet
            |--------------------------------------------------------------------------
            */

            $wallet = Wallet::where(
                    'user_id',
                    $workerId
                )
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Activate Wallet If Needed
            |--------------------------------------------------------------------------
            */

            if ($wallet->status !== 'active') {

                $wallet->update([
                    'status' => 'active'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Capture Balance Before Update
            |--------------------------------------------------------------------------
            */

            $before = $wallet->balance;

            /*
            |--------------------------------------------------------------------------
            | Update Wallet Balance
            |--------------------------------------------------------------------------
            */

            $wallet->increment('balance', $amount);

            $wallet->increment('total_charged', $amount);

            $wallet->refresh();

            /*
            |--------------------------------------------------------------------------
            | Create Transaction Record
            |--------------------------------------------------------------------------
            */

            return WalletTransaction::create([
                'wallet_id'       => $wallet->id,
                'order_id'        => null,
                'type'            => 'topup',
                'amount'          => $amount,
                'balance_before'  => $before,
                'balance_after'   => $wallet->balance,
                'performed_by'    => $adminId,
                'note'            => $note,
            ]);
        });
    }

    /**
     * ----------------------------------------------------------------------
     * Deduct Job Fee
     * ----------------------------------------------------------------------
     *
     * Deducts platform job fee from worker wallet based on career rules.
     * Uses idempotency key to prevent duplicate charges.
     *
     * @param int $workerId
     * @param int $jobId
     * @param int $careerId
     * @param string $idempotencyKey
     *
     * @return WalletTransaction|null
     * @throws Exception
     */
    public function deductJobFee(
        int $workerId,
        int $orderId,
        int $careerId,
        string $idempotencyKey
    ) {

        return DB::transaction(function () use (
            $workerId,
            $orderId,
            $careerId,
            $idempotencyKey
        ) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Deduction
            |--------------------------------------------------------------------------
            */

            if (WalletTransaction::where(
                'idempotency_key',
                $idempotencyKey
            )->exists()) {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Wallet
            |--------------------------------------------------------------------------
            */

            $wallet = Wallet::where(
                    'user_id',
                    $workerId
                )
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Validate Wallet Status
            |--------------------------------------------------------------------------
            */

            if ($wallet->status !== 'active') {

                throw new Exception('Wallet suspended');
            }

            /*
            |--------------------------------------------------------------------------
            | Get Fee Rule
            |--------------------------------------------------------------------------
            */

            $rule = JobFeeRule::where(
                    'career_id',
                    $careerId
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();

            if (!$rule) {

                throw new Exception(
                    "No job fee rule defined for career_id: {$careerId}"
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Balance
            |--------------------------------------------------------------------------
            */

            $amount = $rule->fee;

            if ($wallet->balance < $amount) {

                throw new Exception('Insufficient balance');
            }

            /*
            |--------------------------------------------------------------------------
            | Capture Balance Before Update
            |--------------------------------------------------------------------------
            */

            $before = $wallet->balance;

            /*
            |--------------------------------------------------------------------------
            | Deduct Fee
            |--------------------------------------------------------------------------
            */

            $wallet->decrement('balance', $amount);

            $wallet->increment('total_spent', $amount);

            $wallet->refresh();

            /*
            |--------------------------------------------------------------------------
            | Store Transaction
            |--------------------------------------------------------------------------
            */

            return WalletTransaction::create([
                'wallet_id'       => $wallet->id,
                'order_id'        => $orderId,
                'type'            => 'job_fee',
                'amount'          => $amount,
                'balance_before'  => $before,
                'balance_after'   => $wallet->balance,
                'reference_type'  => 'order',
                'reference_id'    => $orderId,
                'idempotency_key' => $idempotencyKey,
                'note'            => "Fee for order #{$orderId}",
                        ]);
        });
    }

    /**
     * ----------------------------------------------------------------------
     * Check Worker Wallet Availability
     * ----------------------------------------------------------------------
     *
     * Suspends or activates worker wallet based on minimum balance rule.
     *
     * @param int $workerId
     * @return void
     * @throws Exception
     */
  public function checkWorkerAvailability(int $workerId): void
{
    $worker = Worker::with(['career.jobFeeRule'])->findOrFail($workerId);

    $wallet = Wallet::where('user_id', $worker->user_id)->first();

    if (!$wallet) {
        throw new Exception('Wallet not found');
    }

    $minimumRequired = $worker->career?->jobFeeRule?->fee;

    if (is_null($minimumRequired)) {
        throw new Exception('Job fee rule not found for this career');
    }

    if ($wallet->balance < $minimumRequired) {

        $wallet->update([
            'status' => 'suspended'
        ]);

        return;
    }

    $wallet->update([
        'status' => 'active'
    ]);
}
}