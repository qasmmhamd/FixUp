<?php

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_can_be_topup(): void
    {
        
        $user = User::factory()->create([
            'role' => 'admin'
        ]);


        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'status' => 'active'
        ]);

        
        $this->actingAs($user);

        $response = $this->postJson("/api/admin/wallet/topup/{$wallet->id}", [
            'amount' => 100,
        ]);

        $response->assertStatus(200);
    }
}