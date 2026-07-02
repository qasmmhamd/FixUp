<?php
namespace Database\Factories;

use App\Models\AreaAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaAddressFactory extends Factory
{
    protected $model = AreaAddress::class;

   public function definition(): array
{
    return [
        'area_name' => fake()->city(),
    ];
}
}