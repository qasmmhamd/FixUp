<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;


class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   public function test_user_can_create_order()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/order', [
            'description' => 'Fix sink',
            'career_id' => 1,
            'services' => [1],
            'address_id' => 1,
            'priority' => false, // 👈 مهم

        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
    ]);
}
}
