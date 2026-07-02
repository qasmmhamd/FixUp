<?php
namespace Database\Factories;

use App\Models\PriceOffer;
use App\Models\Order;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceOfferFactory extends Factory
{
    protected $model = PriceOffer::class;

  public function definition(): array
{
    return [
        'title' => fake()->sentence(),
    ];
}
}