<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

  public function test_new_users_can_register()
{
    $area = \App\Models\AreaAddress::create([
        'area_name' => 'Test Area'
    ]);

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',

        'phone_number' => '0999999999',
        'birth_date' => '2000-01-01',
        'latitude' => 36.2,
        'longitude' => 37.1,
        'detailed_address' => 'Damascus',

        'area_address_id' => $area->id,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'user'
        ]);
}

    public function test_users_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        // create Sanctum token manually
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(204);
    }
}