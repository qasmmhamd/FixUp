<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Worker;
use App\Models\PriceOffer;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

class AcceptPriceOfferService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    public function execute(
        int $orderId,
        int $offerId
    ): Order {

        return DB::transaction(function () use (
            $orderId,
            $offerId
        ) {

            $offer = PriceOffer::with('order')
                ->lockForUpdate()
                ->findOrFail($offerId);

            $order = Order::lockForUpdate()
                ->findOrFail($orderId);

            if ($order->user_id !== Auth::id()) {
                throw new AuthorizationException(
                    'You are not allowed to accept this offer'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            if ($offer->order_id !== $order->id) {
                throw new Exception('Offer does not belong to this order');
            }

            if ($order->status !== 'pending') {
                throw new Exception('Order already processed');
            }

            if ($offer->status !== 'pending') {
                throw new Exception('Offer already processed');
            }

            /*
            |--------------------------------------------------------------------------
            | Deduct platform fee
            |--------------------------------------------------------------------------
            */

           $worker =Worker::findOrFail($offer->worker_id);
            $userId = $worker->user_id;

            /*
            | Deduct fee
            */
            $this->walletService->deductJobFee(
                $userId,
                $order->id,
                $order->career_id,
                'job_' . $order->id . '_' . $userId
            );

            /*
            |--------------------------------------------------------------------------
            | Check worker availability after deduction
            |--------------------------------------------------------------------------
            */

            $this->walletService->checkWorkerAvailability(
                $offer->worker_id
            );

            /*
            |--------------------------------------------------------------------------
            | Accept offer
            |--------------------------------------------------------------------------
            */

            $offer->update([
                'status' => 'accepted'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update order
            |--------------------------------------------------------------------------
            */

            $order->update([
                'status' => 'accepted'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reject other offers
            |--------------------------------------------------------------------------
            */

            PriceOffer::where('order_id', $order->id)
                ->where('id', '!=', $offer->id)
                ->update([
                    'status' => 'rejected'
                ]);

            return $order->fresh();
        });
    }

    protected function calculateFee(Order $order): int
    {
        /*
        |--------------------------------------------------------------------------
        | حسب المهنة
        |--------------------------------------------------------------------------
        */

        return $order->career->job_fee;
    }
}