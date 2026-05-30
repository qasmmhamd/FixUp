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

/**
 * @class AcceptPriceOfferService
 *
 * Handles the business logic of accepting a price offer in the system.
 *
 * This service is responsible for:
 * - Validating ownership and authorization
 * - Ensuring data consistency using DB transactions
 * - Deducting platform/job fees from worker wallet
 * - Updating order and offer statuses
 * - Rejecting all competing offers
 *
 * It acts as a core domain service in the order lifecycle.
 */
class AcceptPriceOfferService
{
    /**
     * Wallet service dependency
     *
     * @var WalletService
     */
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Accept a specific price offer for an order.
     *
     * This method ensures full transactional integrity:
     * - Locks order and offer records
     * - Validates authorization and ownership
     * - Deducts job fee from worker wallet
     * - Updates order and offer status
     * - Rejects other competing offers
     *
     * @param int $orderId
     * @param int $offerId
     * @return Order
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function execute(int $orderId, int $offerId): Order
    {
        return DB::transaction(function () use ($orderId, $offerId) {

            /*
            |----------------------------------------------------------
            | Fetch Offer & Order with Row Locking
            |----------------------------------------------------------
            */

            $offer = PriceOffer::with('order')
                ->lockForUpdate()
                ->findOrFail($offerId);

            $order = Order::lockForUpdate()
                ->findOrFail($orderId);

            /*
            |----------------------------------------------------------
            | Authorization Check (Customer Ownership)
            |----------------------------------------------------------
            */

            if ($order->user_id !== Auth::id()) {
                throw new AuthorizationException(
                    'You are not allowed to accept this offer'
                );
            }

            /*
            |----------------------------------------------------------
            | Business Validation Rules
            |----------------------------------------------------------
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
            |----------------------------------------------------------
            | Resolve Worker Account
            |----------------------------------------------------------
            */

            $worker = Worker::findOrFail($offer->worker_id);
            $userId = $worker->user_id;

            /*
            |----------------------------------------------------------
            | Deduct Job Fee From Worker Wallet
            |----------------------------------------------------------
            */

            $this->walletService->deductJobFee(
                $userId,
                $order->id,
                $order->career_id,
                'job_' . $order->id . '_' . $userId
            );

            /*
            |----------------------------------------------------------
            | Validate Worker Financial Availability
            |----------------------------------------------------------
            */

            $this->walletService->checkWorkerAvailability(
                $offer->worker_id
            );

            /*
            |----------------------------------------------------------
            | Accept Selected Offer
            |----------------------------------------------------------
            */

            $offer->update([
                'status' => 'accepted'
            ]);

            /*
            |----------------------------------------------------------
            | Update Order State
            |----------------------------------------------------------
            */

            $order->update([
                'status' => 'accepted'
            ]);

            /*
            |----------------------------------------------------------
            | Reject Competing Offers
            |----------------------------------------------------------
            */

            PriceOffer::where('order_id', $order->id)
                ->where('id', '!=', $offer->id)
                ->update([
                    'status' => 'rejected'
                ]);

            /*
            |----------------------------------------------------------
            | Return Fresh State
            |----------------------------------------------------------
            */

            return $order->fresh();
        });
    }

    /**
     * Calculate platform/job fee based on order career.
     *
     * @param Order $order
     * @return int
     */
    protected function calculateFee(Order $order): int
    {
        return $order->career->job_fee;
    }
}