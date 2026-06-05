<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AreaAddressSeeder::class,
            CareerSeeder::class,
            ServiceSeeder::class,
            MessageTopicSeeder::class,
            MessageTemplateSeeder::class,
            JobFeeRuleSeeder::class,

            UserSeeder::class,
            WorkerSeeder::class,
            AddressSeeder::class,
            WorkerServiceSeeder::class,
            WalletSeeder::class,

            OrderSeeder::class,
            PriceOfferSeeder::class,

            ConversationSeeder::class,
            ChatMessageSeeder::class,

            RatingSeeder::class,

            NotificationSeeder::class,
            UserDeviceSeeder::class,
            WalletTransactionSeeder::class,
                ]);
    }
}
