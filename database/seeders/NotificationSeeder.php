<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [

            'عرض سعر جديد',
            'تم قبول الطلب',
            'تم اكتمال الخدمة',
            'هناك عامل جديد',
            'رسالة جديدة'

        ];

        foreach (User::all() as $user) {

            for ($i = 1; $i <= rand(3,10); $i++) {

                Notification::create([

                    'user_id' => $user->id,

                    'title' =>
                        fake()->randomElement($titles),

                    'body' =>
                        'لديك إشعار جديد في تطبيق FixUp',

                    'type' =>
                        fake()->randomElement([
                            'new_request',
                            'price_offer',
                            'chat',
                            'new_worker'
                            
                        ]),

                    'is_read' =>
                        fake()->boolean(60),

                    'data' => [
                        'generated' => true
                    ]
                ]);
            }
        }
    }
}