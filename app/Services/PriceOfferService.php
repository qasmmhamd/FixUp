<?php

namespace App\Services;

use App\Models\PriceOffer;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;

class PriceOfferService
{
    public function create(array $data)
    {
        $worker = Worker::where('user_id', Auth::id())->firstOrFail();

        return PriceOffer::create([
            'order_id'   => $data['order_id'],
            'worker_id'  => $worker->id,
            'time_range' => $data['time_range'],
            'price'      => $data['price'],
            'status'     => 'pending',
        ]);
    }
}