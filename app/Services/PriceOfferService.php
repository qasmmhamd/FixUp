<?php

namespace App\Services;

use App\Models\PriceOffer;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class PriceOfferService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function create(array $data)
    {
        $worker = Worker::where('user_id', Auth::id())->firstOrFail();

        // 🔴 1. منع التكرار (حل الخطأ الحالي)
        $existingOffer = PriceOffer::where('order_id', $data['order_id'])
            ->where('worker_id', $worker->id)
            ->first();

        if ($existingOffer) {
            return response()->json([
                'message' => 'لقد قمت بإرسال عرض لهذا الطلب مسبقًا'
            ], 400);
        }

        // ✅ 2. إنشاء العرض
        $offer = PriceOffer::create([
            'order_id'   => $data['order_id'],
            'worker_id'  => $worker->id,
            'time_range' => $data['time_range'],
            'price'      => $data['price'],
            'status'     => 'pending',
        ]);

        $offer->load('order.user');

        $customer = $offer->order->user;

        // 🔥 3. إرسال الإشعار (بدون شرط)
        $this->notificationService->send(
            $customer,
            "عرض سعر جديد 💰",
            "تم إرسال عرض سعر لطلبك",
            "price_offer",
            [
                "order_id" => $offer->order_id,
                "offer_id" => $offer->id
            ]
        );

        return $offer;
    }

    public function getOrderWithOffers(int $orderId, int $userId)
    {
        return Order::with('priceOffers')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}