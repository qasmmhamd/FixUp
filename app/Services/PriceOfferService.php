<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
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
    | Create Price Offer
    |--------------------------------------------------------------------------
    */

    public function create(array $data)
    {
        /*
        |----------------------------------------------------------------------
        | Get Worker
        |----------------------------------------------------------------------
        */

        $worker = Worker::query()
            ->select('id')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        /*
        |----------------------------------------------------------------------
        | Prevent Duplicate Offers
        |----------------------------------------------------------------------
        */

        $existingOffer = PriceOffer::query()
            ->where('order_id', $data['order_id'])
            ->where('worker_id', $worker->id)
            ->exists();

        if ($existingOffer) {

            abort(
                400,
                'لقد قمت بإرسال عرض لهذا الطلب مسبقًا'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Create Offer
        |----------------------------------------------------------------------
        */

        $offer = PriceOffer::query()
            ->create([
                'order_id'   => $data['order_id'],
                'worker_id'  => $worker->id,
                'time_range' => $data['time_range'],
                'price'      => $data['price'],
                'status'     => 'pending',
            ]);

        /*
        |----------------------------------------------------------------------
        | Get Customer
        |----------------------------------------------------------------------
        */

        $customer = Order::query()
            ->with('user:id')
            ->select('id', 'user_id')
            ->findOrFail($offer->order_id)
            ->user;

        /*
        |----------------------------------------------------------------------
        | Send Notification In Background
        |----------------------------------------------------------------------
        */

        SendNotificationJob::dispatch(
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
    | Get Order With Offers (Customer View)
    |--------------------------------------------------------------------------
    */

    public function getOrderWithOffers(
        int $orderId,
        int $userId
    ) {

        return Order::query()
            ->with([
                'priceOffers.worker'
            ])
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Worker Offers
    |--------------------------------------------------------------------------
    */

    public function getWorkerOffers(
        int $userId
    ) {

        $worker = Worker::query()
            ->select('id')
            ->where('user_id', $userId)
            ->firstOrFail();

        return PriceOffer::query()
            ->with([
                'order'
            ])
            ->where('worker_id', $worker->id)
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Accepted Offers
    |--------------------------------------------------------------------------
    */

    public function getAcceptedOffers(
        int $userId
    ) {

        $worker = Worker::query()
            ->select('id')
            ->where('user_id', $userId)
            ->firstOrFail();

        return PriceOffer::query()
            ->with([
                'order',
                'order.user',
                'order.address.areaAddress'
            ])
            ->where('worker_id', $worker->id)
            ->where('status', 'accepted')
            ->latest()
            ->get();
    }
}