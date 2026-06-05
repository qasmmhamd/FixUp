<?php

namespace Database\Seeders;

use App\Models\MessageTopic;
use Illuminate\Database\Seeder;

class MessageTopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'استفسار عن السعر',
            'تحديد موعد',
            'تفاصيل الخدمة',
            'تأكيد التنفيذ',
            'تعديل الطلب',
            'متابعة الطلب',
        ];

        foreach ($topics as $topic) {
            MessageTopic::firstOrCreate([
                'topic' => $topic
            ]);
        }
    }
}