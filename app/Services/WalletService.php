<?php

namespace App\Services;

use App\Models\JobFeeRule;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Order;
use App\Models\Worker;

use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
   public function topUp($workerId, $amount, $adminId, $note = null)
{
    return DB::transaction(function () use ($workerId, $amount, $adminId, $note) {

        $wallet = Wallet::where('user_id', $workerId)
            ->lockForUpdate()
            ->firstOrFail();

        // ✅ إجبار تفعيل المحفظة عند أي شحن
        if ($wallet->status !== 'active') {
            $wallet->update([
                'status' => 'active'
            ]);
        }

        $before = $wallet->balance;

        $wallet->increment('balance', $amount);
        $wallet->increment('total_charged', $amount);
        $wallet->refresh();

        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'topup',
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => $wallet->balance,
            'performed_by' => $adminId,
            'note' => $note,
        ]);
    });
}

    /**
     * Deduct job fee based on career_id
     */
    public function deductJobFee($workerId, $jobId, $careerId, $idempotencyKey)
    {
        return DB::transaction(function () use ($workerId, $jobId, $careerId, $idempotencyKey) {

            // prevent double charging
            if (WalletTransaction::where('idempotency_key', $idempotencyKey)->exists()) {
                return null;
            }

            $wallet = Wallet::where('user_id', $workerId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->status !== 'active') {
                throw new Exception('Wallet suspended');
            }

           $rule = JobFeeRule::where('career_id', $careerId)
                ->where('is_active', true)
                ->first();
                

            if (! $rule) {
                throw new Exception("No job fee rule defined for career_id: {$careerId}");
            }

            $amount = $rule->fee;

            if ($wallet->balance < $amount) {
                throw new Exception('Insufficient balance');
            }

            $before = $wallet->balance;

            $wallet->decrement('balance', $amount);
            $wallet->increment('total_spent', $amount);
            $wallet->refresh();

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'job_fee',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $wallet->balance,
                'reference_type' => 'job',
                'reference_id' => $jobId,
                'idempotency_key' => $idempotencyKey,
                'note' => "Fee for career #{$careerId}",
            ]);
        });
    }
   public function checkWorkerAvailability(int $workerId): void
{
    $worker = Worker::findOrFail($workerId);

    // مهم: ناخذ user_id من جدول العمال
    $userId = $worker->user_id;

    $wallet = Wallet::where('user_id', $userId)->first();

    if (!$wallet) {
        throw new \Exception('Wallet not found');
    }

    $minimumRequired = 10;

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