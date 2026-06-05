<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Database\Seeder;

class ChatMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [

            'مرحباً، متى يمكن البدء بالعمل؟',
            'يمكنني الحضور اليوم.',
            'هل السعر يشمل المواد؟',
            'نعم يشمل جميع المواد.',
            'ممتاز، بانتظارك.',
            'تم تأكيد الموعد.',
            'سأصل خلال ساعة.',
            'تم الانتهاء من العمل.',
            'شكراً لكم.',
            'الخدمة ممتازة.',

        ];

        foreach (Conversation::all() as $conversation) {

            for ($i = 1; $i <= rand(3, 8); $i++) {

                ChatMessage::create([

                    'conversation_id' => $conversation->id,

                    'sender_id' => rand(0,1)
                        ? $conversation->customer_id
                        : $conversation->worker->user_id,

                    'message' =>
                        fake()->randomElement($messages),

                    'message_type' => 'text',
                ]);
            }
        }
    }
}