<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
                'images' // مهم لعرض الصور
            ]);
        });
    }

    /**
     * معالجة العنوان (قديم أو جديد)
     */
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
}