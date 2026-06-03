<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Image;
use App\Models\Order;
use App\Models\Worker;
use App\Models\Rating;
use App\Models\PriceOffer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


/**
 * Class OrderService
 *
 * Core business logic for order lifecycle in the FixUp platform.
 *
 * Responsibilities:
 * - Creating customer orders
 * - Managing addresses (new or existing)
 * - Attaching services and images
 * - Notifying matching workers
 * - Finding eligible worker orders based on service compatibility
 *
 * This service is the central hub of the order system and is responsible
 * for both persistence and domain-level orchestration.
 */
class OrderService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Create a new order for a customer.
     *
     * Flow:
     * 1. Resolve or create address
     * 2. Create order record
     * 3. Attach services
     * 4. Store images
     * 5. Notify workers after commit
     *
     * @param array $data
     * @param int $user_id
     * @return Order
     */
    public function create(
        array $data,
        int $user_id
    ): Order {

        $order = DB::transaction(function () use ($data, $user_id) {

            /**
             * Resolve address (new or existing)
             */
            $addressId = $this->handleAddress($data, $user_id);

            /**
             * Create order record
             */
            $order = Order::create([
                'user_id'      => $user_id,
                'description'  => $data['description'],
                'address_id'   => $addressId,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'career_id'    => $data['career_id'],
                'priority'     => $data['priority'] ?? false,
                'expires_at'   => now()->addHours(12),
            ]);

            /**
             * Attach selected services
             */
            $order->services()->attach($data['services']);

            /**
             * Persist uploaded images
             */
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {

                    $path = $image->store('orders', 'public');

                    Image::create([
                        'order_id' => $order->id,
                        'path'     => $path,
                    ]);
                }
            }

            return $order;
        });

        /**
         * Post-transaction notification (ensures DB consistency)
         */
        DB::afterCommit(function () use ($order) {
            $this->notifyWorkers($order);
        });

        return $order->load([
            'services',
            'address',
            'worker',
            'user',
            'career',
            'images'
        ]);
    }

    /**
     * Resolve order address.
     *
     * - Creates new address if full address payload exists
     * - Otherwise uses existing address_id
     *
     * @param array $data
     * @param int $user_id
     * @return int address_id
     */
    private function handleAddress(
        array $data,
        int $user_id
    ): int {

        if (!empty($data['address'])) {

            $address = Address::create([
                'user_id'          => $user_id,
                'latitude'         => $data['address']['latitude'],
                'longitude'        => $data['address']['longitude'],
                'detailed_address' => $data['address']['detailed_address'],
                'area_address_id'  => $data['address']['area_address_id'] ?? null,
            ]);

            return $address->id;
        }

        return $data['address_id'];
    }

    /**
     * Notify all eligible workers about a new order.
     *
     * Criteria:
     * - Same career
     * - Worker is active
     *
     * @param Order $order
     * @return void
     */
    private function notifyWorkers(Order $order): void
    {
        $workers = Worker::with('user')
            ->where('career_id', $order->career_id)
            ->where('status', 'active')
            ->get();

        foreach ($workers as $worker) {

            $user = $worker->user;

            if (!$user) {
                continue;
            }

            $this->notificationService->send(
                $user,
                "طلب جديد 🛠️",
                "يوجد طلب جديد مناسب لك",
                "new_order",
                [
                    "order_id" => $order->id
                ]
            );
        }
    }

    /**
     * Get orders that match a worker's services and career.
     *
     * Matching logic:
     * - Same career
     * - Pending status
     * - All required services must be satisfied
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMatchingOrdersForWorker(
        int $userId
    ) {

        $worker = Worker::with('services')
            ->where('user_id', $userId)
            ->firstOrFail();

        $workerServiceIds = $worker->services->pluck('id');

        return Order::where('career_id', $worker->career_id)
            ->where('status', 'pending')
            ->whereHas('services', function ($query) use ($workerServiceIds) {
                $query->whereIn('services.id', $workerServiceIds);
            })
            ->withCount('services')
            ->withCount([
                'services as matched_services_count' => function ($query) use ($workerServiceIds) {
                    $query->whereIn('services.id', $workerServiceIds);
                }
            ])
            ->having('services_count', '=', DB::raw('matched_services_count'))
            ->get();
    }
    /**
 * Cancel an order if it belongs to the authenticated user.
 *
 * Business Logic:
 * - Verifies that the order belongs to the user.
 * - Prevents cancellation if order is already accepted or completed.
 * - Updates order status to "cancelled".
 *
 * @param int $orderId The ID of the order.
 * @param int $userId The ID of the authenticated user.
 * @return Order Returns the updated order model.
 *
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
 *
 * @example Internal Usage:
 * $this->orderService->cancelOrder($orderId, $userId);
 */
    public function cancelOrder(int $orderId, int $userId): Order
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        abort_unless($order, 404, 'The order does not exist');

        abort_if(
            $order->status !== 'pending',
            403,
            'The order cannot be cancelled at its current status'
        );

        $order->update([
            'status' => 'cancelled'
        ]);

        return $order->fresh();
    }
    /**
     * Get user orders with optional status filter.
     *
     * @param int $userId
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserOrders(
            int $userId,
            ?string $status = null
        )
        {
            return Order::query()
                ->where('user_id', $userId)

                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                })

                ->with([
                    'services',
                    'address',
                    'offers',
                    'images'
                ])

                ->latest()
                ->get();
        }
        /**
         * Create a rating for a completed order.
         *
         * Ensures the order belongs to the user, is completed,
         * and has not been rated before.
         *
         * @param int $userId
         * @param array $data
         * @return Rating
         *
         * @throws ValidationException
         */
                
        public function createRating(int $userId, array $data): Rating
        {
            $order = Order::where('id', $data['order_id'])
                ->where('user_id', $userId)
                ->firstOrFail();

            if ($order->status !== 'completed') {
                throw ValidationException::withMessages([
                    'order_id' => 'You can only rate completed orders.',
                ]);
            }

            if (Rating::where('order_id', $order->id)->exists()) {
                throw ValidationException::withMessages([
                    'order_id' => 'This order has already been rated.',
                ]);
            }

            $acceptedOffer = PriceOffer::where('order_id', $order->id)
                ->where('status', 'accepted')
                ->first();

            if (!$acceptedOffer) {
                throw ValidationException::withMessages([
                    'order_id' => 'No accepted offer found for this order.',
                ]);
            }

              $order->update([
                    'is_rating' => true,
                ]);

            $rating = Rating::create([
                'user_id'   => $userId,
                'worker_id' => $acceptedOffer->worker_id,
                'order_id'  => $order->id,
                'rate'      => $data['rate'],
            ]);

            $order->update([
                'is_rating' => true,
            ]);

            return $rating;           
            
        }
       
        public function requestCompletion(int $orderId, int $authUserId): Order
        {
            return DB::transaction(function () use ($orderId, $authUserId) {

                $order = Order::lockForUpdate()->findOrFail($orderId);

                if ($order->status !== 'accepted') {
                    throw new \Exception('Order must be accepted first');
                }

                $acceptedOffer =PriceOffer::where('order_id', $order->id)
                    ->where('status', 'accepted')
                    ->first();

                if (!$acceptedOffer) {
                    throw new \Exception('No accepted offer found');
                }

                $worker =Worker::findOrFail($acceptedOffer->worker_id);

                if ($worker->user_id !== $authUserId) {
                    throw new \Exception('Only assigned worker can request completion');
                }

                $order->update([
                    'status' => 'completion_requested'
                ]);

                return $order->fresh();
            });
        }
        public function markAsCompleted(int $orderId, int $userId): Order
        {
            return DB::transaction(function () use ($orderId, $userId) {

                $order = Order::lockForUpdate()->findOrFail($orderId);

                // 1. فقط صاحب الطلب
                if ($order->user_id !== $userId) {
                    throw new \Exception('Only order owner can complete the order');
                }

                // 2. شرط مهم: لازم تكون الحالة "completion_requested"
                if ($order->status !== 'completion_requested') {
                    throw new \Exception('Order must be in completion_requested state before marking as completed');
                }

                // 3. تحديث الحالة
                $order->update([
                    'status' => 'completed'
                ]);

                return $order->fresh();
            });
        }
}