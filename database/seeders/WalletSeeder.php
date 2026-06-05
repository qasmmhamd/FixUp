<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {

            $charged = rand(1000, 10000);

            $spent = rand(0, $charged);

            Wallet::create([

                'user_id' => $user->id,

                'balance' => $charged - $spent,

                'total_charged' => $charged,

                'total_spent' => $spent,

                'status' => 'active',

            ]);
        }
    }
}