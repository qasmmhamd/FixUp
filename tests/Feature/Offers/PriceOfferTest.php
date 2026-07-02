<?php

use App\Models\User;
use App\Models\Worker;
use App\Models\Order;
use App\Models\Career;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceOfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_worker_can_send_offer(): void
    {
        $career = Career::create([
            'name' => 'Electrician',
        ]);

        $workerUser = User::factory()->create([
            'role' => 'worker',
        ]);

        Worker::create([
            'user_id'   => $workerUser->id,
            'career_id' => $career->id,
        ]);

        $order = Order::factory()->create();

        $this->actingAs($workerUser);

        $response = $this->postJson('/api/price-offers', [
            'order_id'   => $order->id,
            'price'      => 100,
            'time_range' => '2h',
        ]);

        $response->assertStatus(201);
    }
}