<?php
use App\Models\User;
use App\Models\Career;
use App\Models\Service;
use App\Models\AreaAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order(): void
    {
        $user = User::factory()->create();

        $career = Career::create(['name' => 'Electrician']);
        $service = Service::create([
            'name' => 'Repair',
            'career_id' => $career->id
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/order', [
            'description' => 'Fix issue',
            'priority' => true,
            'career_id' => $career->id,
            'services' => [$service->id],
            'address' => [
                'latitude' => 33.5,
                'longitude' => 36.2,
                'detailed_address' => 'Damascus',
                'area_address_id' => AreaAddress::create(['area_name'=>'A'])->id
            ]
        ]);

        $response->assertStatus(201);
    }
}