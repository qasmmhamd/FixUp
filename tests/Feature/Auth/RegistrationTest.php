<?php

namespace Tests\Feature\Auth;


use App\Models\AreaAddress;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    
public function test_new_users_can_register(): void
{
    $area = AreaAddress::create([
        'area_name' => 'Test Area',
    ]);

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',

        'phone_number' => '0999999999',
        'birth_date' => '2000-01-01',
        'latitude' => 33.5138,
        'longitude' => 36.2765,
        'detailed_address' => 'Damascus',

        'area_address_id' => $area->id,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'user',
        ]);
}
}
