<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Worker;
use App\Models\PriceOffer;
use Illuminate\Support\Facades\Auth;

class PriceOfferService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | إنشاء عرض سعر
    |--------------------------------------------------------------------------
    */

    public function create(array $data)
    {
        $worker = Worker::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        // منع التكرار
        $existingOffer = PriceOffer::where(
            'order_id',
            $data['order_id']
        )
        ->where(
            'worker_id',
            $worker->id
        )
        ->first();

        if ($existingOffer) {

            abort(400, 'لقد قمت بإرسال عرض لهذا الطلب مسبقًا');
        }

        // إنشاء العرض
        $offer = PriceOffer::create([
            'order_id'   => $data['order_id'],
            'worker_id'  => $worker->id,
            'time_range' => $data['time_range'],
            'price'      => $data['price'],
            'status'     => 'pending',
        ]);

        $offer->load('order.user');

        $customer = $offer->order->user;

        // إرسال إشعار
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

    /*
    |--------------------------------------------------------------------------
    | عرض عروض الطلب لصاحب الطلب
    |--------------------------------------------------------------------------
    */

    public function getOrderWithOffers(
        int $orderId,
        int $userId
    ) {
        return Order::with([
            'priceOffers.worker'
        ])
        ->where('id', $orderId)
        ->where('user_id', $userId)
        ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | جميع عروض العامل
    |--------------------------------------------------------------------------
    */

    public function getWorkerOffers(
        int $userId
    ) {
        $worker = Worker::where(
            'user_id',
            $userId
        )->firstOrFail();

        return PriceOffer::with([
            'order',
        ])
        ->where(
            'worker_id',
            $worker->id
        )
        ->latest()
        ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | العروض المقبولة فقط
    |--------------------------------------------------------------------------
    */

    public function getAcceptedOffers(
        int $userId
    ) {
        $worker = Worker::where(
            'user_id',
            $userId
        )->firstOrFail();

        return PriceOffer::with([
            'order',
            'order.user',
            'order.address.areaAddress'
            
        ])
        ->where(
            'worker_id',
            $worker->id
        )
        ->where(
            'status',
            'accepted'
        )
        ->latest()
        ->get();
    }
}