<?php

namespace App\Services;

use App\Models\Request;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $data, int $user_id): Order
    {
        return DB::transaction(function () use ($data, $user_id) {

            // 🔹 معالجة العنوان
            $addressId = $this->handleAddress($data, $user_id);

            // 🔹 إنشاء الطلب
            $request = Order::create([
                'user_id' => $user_id,
                'description' => $data['description'],
                'address_id' => $addressId,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'expires_at' => now()->addHours(12),
            ]);

            // 🔹 ربط الخدمات
            $request->services()->attach($data['services']);

            // 🔹 تحميل العلاقات
            return $request->load([
                'services',
                'address',
                'worker',
                'user'
            ]);
        });
    }

    /**
     * معالجة العنوان (قديم أو جديد)
     */
    private function handleAddress(array $data, int $user_id): int
    {
        // إذا عنوان جديد
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

        // إذا عنوان موجود
        return $data['address_id'];
    }
}