<?php
/*
namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Worker;


class OrderService
{
    public function create(array $data, int $user_id): Order
    {
        return DB::transaction(function () use ($data, $user_id) {

            // 🔹 معالجة العنوان
            $addressId = $this->handleAddress($data, $user_id);

            // 🔹 إنشاء الطلب
            $order = Order::create([
                'user_id' => $user_id,
                'description' => $data['description'],
                'address_id' => $addressId,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'career_id' => $data['career_id'],
                'priority' => $data['priority'] ?? false,
                'expires_at' => now()->addHours(12),
            ]);

            // 🔹 ربط الخدمات
            $order->services()->attach($data['services']);

            // 🔹 رفع الصور
            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {

                    $path = $image->store('orders', 'public');

                    Image::create([
                        'order_id' => $order->id,
                        'path' => $path,
                    ]);
                }
            }

            // 🔹 تحميل العلاقات
            return $order->load([
                'services',
                'address',
                'worker',
                'user',
                'career',
                'images' // مهم لعرض الصور
            ]);
        });
    }

    /**
     * معالجة العنوان (قديم أو جديد)
     */
    /*
    private function handleAddress(array $data, int $user_id): int
    {
        if (isset($data['address'])) {
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
        return DB::transaction(function () use ($data, $user_id) {

            // 🔹 address
            $addressId = $this->handleAddress($data, $user_id);

            // 🔹 create order
            $order = Order::create([
                'user_id' => $user_id,
                'description' => $data['description'],
                'address_id' => $addressId,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'career_id' => $data['career_id'],
                'priority' => $data['priority'] ?? false,
                'expires_at' => now()->addHours(12),
            ]);

            // 🔹 services
            $order->services()->attach($data['services']);

            // 🔹 images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $image->store('orders', 'public');

                    Image::create([
                        'order_id' => $order->id,
                        'path' => $path,
                    ]);
                }
            }

            // 🔥 إرسال إشعارات للعمال
            $this->notifyWorkers($order);

            return $order->load([
                'services',
                'address',
                'worker',
                'user',
                'career',
                'images'
            ]);
        });
    }

    private function handleAddress(array $data, int $user_id): int
    {
        if (isset($data['address'])) {
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
     * 🔥 إشعار العمال
     */
    /*
    private function notifyWorkers(Order $order)
    {
        $workers = Worker::with('user')
            ->where('career_id', $order->career_id)
            ->where('status', 'active')
            ->get();

        foreach ($workers as $worker) {
            if ($worker->user) {
                $this->notificationService->send(
                    $worker->user,
                    "طلب جديد 🛠️",
                    "يوجد طلب خدمة جديد",
                    "new_order",
                    [
                        "order_id" => $order->id
                    ]
                );
            }
        }
    }
}*/

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
        return DB::transaction(function () use ($data, $user_id) {

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

            // 🔥 إشعار العمال المناسبين
            $this->notifyWorkers($order);

            return $order->load([
                'services',
                'address',
                'worker',
                'user',
                'career',
                'images'
            ]);
        });
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
     * 🔥 Notify workers (IMPROVED)
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

            // 🔥 مهم: تأكد من وجود token
            if (empty($user->fcm_token)) {
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
}