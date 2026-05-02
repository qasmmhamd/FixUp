<?php

namespace App\Services;

use App\Models\PriceOffer;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Notifications\NewPriceOfferNotification;

class PriceOfferService
{
    public function create(array $data)
    {
        $worker = Worker::where('user_id', Auth::id())->firstOrFail();

         $offer = PriceOffer::create([
        'order_id'   => $data['order_id'],
        'worker_id'  => $worker->id,
        'time_range' => $data['time_range'],
        'price'      => $data['price'],
        'status'     => 'pending',
        ]);

        $offer->load('order.user');

        $offer->order->user->notify(
        new NewPriceOfferNotification($offer)
    );

    return $offer;

    }
    
          public function getOrderWithOffers(int $orderId, int $userId)
    {
        return Order::with('priceOffers')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}