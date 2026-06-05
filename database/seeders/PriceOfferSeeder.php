<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Worker;
use App\Models\PriceOffer;
use Illuminate\Database\Seeder;

class PriceOfferSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {

            $workers = Worker::where(
                'career_id',
                $order->career_id
            )->inRandomOrder()
             ->take(rand(1, 4))
             ->get();

            $acceptedGiven = false;

            foreach ($workers as $worker) {

                $status = 'pending';

                if (
                    !$acceptedGiven &&
                    in_array(
                        $order->status,
                        ['accepted', 'completed', 'completion_requested']
                    )
                ) {
                    $status = 'accepted';
                    $acceptedGiven = true;
                }

                PriceOffer::create([
                    'worker_id' => $worker->id,
                    'order_id' => $order->id,
                    'price' => rand(500, 50000),
                    'time_range' => rand(1, 5).' أيام',
                    'status' => $status,
                ]);
            }
        }
    }
}