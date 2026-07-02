<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'career_id' => Career::factory(),
            'description' => fake()->sentence(),
            'status' => 'pending',
            'expires_at' => now()->addHours(12),
        ];
    }
}