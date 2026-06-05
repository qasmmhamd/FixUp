<?php

namespace Database\Seeders;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Wallet::all() as $wallet) {

            $balance = 0;

            for ($i = 1; $i <= rand(5,15); $i++) {

                $before = $balance;

                $amount = rand(10000,50000);

                $type = fake()->randomElement([
                    'charge',
                    'job_fee',
                    'refund'
                ]);

                if ($type === 'job_fee') {

                    $balance -= $amount;

                } else {

                    $balance += $amount;
                }

                WalletTransaction::create([

                    'wallet_id' => $wallet->id,

                    'type' => $type,

                    'amount' => $amount,

                    'balance_before' => $before,

                    'balance_after' => $balance,

                    'idempotency_key' => Str::uuid(),

                    'note' =>
                        'عملية مالية تجريبية',
                ]);
            }
        }
    }
}