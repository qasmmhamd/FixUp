<?php

use App\Models\Career;
use App\Models\Order;
use App\Models\PriceOffer;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcceptOfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_accept_offer(): void
    {
        $customer = User::factory()->create();

        $career = Career::create([
            'name' => 'Electrician',
        ]);

        $workerUser = User::factory()->create([
            'role' => 'worker',
        ]);

        $worker = Worker::create([
            'user_id'   => $workerUser->id,
            'career_id' => $career->id,
        ]);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status'  => 'pending',
        ]);

        $offer = PriceOffer::factory()->create([
            'worker_id' => $worker->id,
            'order_id'  => $order->id,
            'status'    => 'pending',
        ]);

        $this->actingAs($customer);

        $response = $this->postJson("api/worker/orders/{$order->id}/offers/{$offer->id}/accept", [
            'offer_id' => $offer->id,
        ]);

        $response->assertStatus(200);
    }
}