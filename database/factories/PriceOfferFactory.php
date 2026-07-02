<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\PriceOffer;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceOfferFactory extends Factory
{
    protected $model = PriceOffer::class;

    public function definition(): array
    {
        return [
            'worker_id' => Worker::factory(),
            'order_id' => Order::factory(),
            'price' => fake()->numberBetween(50, 500),
            'time_range' => '2h',
            'status' => 'pending',
        ];
    }
}