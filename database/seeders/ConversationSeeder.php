<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Conversation;
use App\Models\MessageTopic;
use Illuminate\Database\Seeder;


class ConversationSeeder extends Seeder
{
    public function run(): void
    {

        $orders = Order::with('acceptedOffer')->get();

        foreach ($orders as $order) {

            if (!$order->acceptedOffer) {
                continue;
            }

            Conversation::create([
                'customer_id' => $order->user_id,
                'worker_id' => $order->acceptedOffer->worker_id,
                'topic_id' => MessageTopic::inRandomOrder()->first()->id,
                'status' => 'open',
            ]);
        }
    }
}