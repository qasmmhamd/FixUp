<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::where('status', 'completed')
            ->with('acceptedOffer')
            ->get();

        foreach ($orders as $order) {

            if (!$order->acceptedOffer) {
                continue;
            }

            Rating::create([

                'user_id' => $order->user_id,

                'worker_id' =>
                    $order->acceptedOffer->worker_id,

                'order_id' =>
                    $order->id,

                'rate' =>
                    rand(3,5),
            ]);
        }
    }
}