<?php
namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Image;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function create(array $data, int $user_id): Order
    {
        $order = DB::transaction(function () use ($data, $user_id) {

            // 🔹 Address
            $addressId = $this->handleAddress($data, $user_id);

            // 🔹 Create Order
            $order = Order::create([
                'user_id' => $user_id,
                'description' => $data['description'],
                'address_id' => $addressId,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'career_id' => $data['career_id'],
                'priority' => $data['priority'] ?? false,
                'expires_at' => now()->addHours(12),
            ]);

            // 🔹 Attach services
            $order->services()->attach($data['services']);

            // 🔹 Save images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $image->store('orders', 'public');

                    Image::create([
                        'order_id' => $order->id,
                        'path' => $path,
                    ]);
                }
            }

            return $order;
        });

        // 🔥 مهم جدًا: بعد نجاح العملية فقط
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
     * 🔹 Handle address
     */
    private function handleAddress(array $data, int $user_id): int
    {
        if (!empty($data['address'])) {
            $address = Address::create([
                'user_id' => $user_id,
                'latitude' => $data['address']['latitude'],
                'longitude' => $data['address']['longitude'],
                'detailed_address' => $data['address']['detailed_address'],
                'area_address_id' => $data['address']['area_address_id'] ?? null,
            ]);

            return $address->id;
        }

        return $data['address_id'];
    }

    /**
     * 🔥 Notify workers (FIXED)
     */
    private function notifyWorkers(Order $order)
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

            // 🔥 بدون شرط fcm_token
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

    public function getMatchingOrdersForWorker(int $userId)
    {
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
}