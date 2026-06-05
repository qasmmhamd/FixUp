<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use App\Models\MessageTopic;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            // استفسار عن السعر
            [
                'text' => 'هل السعر يشمل قطع الغيار؟',
                'sender_type' => 'customer',
                'topic' => 'استفسار عن السعر',
            ],
            [
                'text' => 'هل يوجد تكلفة إضافية في حال احتجنا قطع تبديل؟',
                'sender_type' => 'customer',
                'topic' => 'استفسار عن السعر',
            ],
            [
                'text' => 'السعر يشمل أجور العمل فقط.',
                'sender_type' => 'worker',
                'topic' => 'استفسار عن السعر',
            ],
            [
                'text' => 'بعد المعاينة يمكنني إعطاؤك سعراً نهائياً.',
                'sender_type' => 'worker',
                'topic' => 'استفسار عن السعر',
            ],

            // تحديد موعد
            [
                'text' => 'هل يمكنك الحضور صباحاً؟',
                'sender_type' => 'customer',
                'topic' => 'تحديد موعد',
            ],
            [
                'text' => 'هل أنت متاح مساء اليوم؟',
                'sender_type' => 'customer',
                'topic' => 'تحديد موعد',
            ],
            [
                'text' => 'أستطيع تنفيذ الخدمة اليوم.',
                'sender_type' => 'worker',
                'topic' => 'تحديد موعد',
            ],
            [
                'text' => 'أستطيع الحضور خلال ساعة.',
                'sender_type' => 'worker',
                'topic' => 'تحديد موعد',
            ],
            [
                'text' => 'الموعد مناسب بالنسبة لي.',
                'sender_type' => 'customer',
                'topic' => 'تحديد موعد',
            ],
            [
                'text' => 'تم تأكيد الموعد.',
                'sender_type' => 'worker',
                'topic' => 'تحديد موعد',
            ],

            // تفاصيل الخدمة
            [
                'text' => 'هل يمكنك إرسال صورة للمشكلة؟',
                'sender_type' => 'worker',
                'topic' => 'تفاصيل الخدمة',
            ],
            [
                'text' => 'سأقوم بإرسال الصور حالاً.',
                'sender_type' => 'customer',
                'topic' => 'تفاصيل الخدمة',
            ],
            [
                'text' => 'منذ متى بدأت المشكلة؟',
                'sender_type' => 'worker',
                'topic' => 'تفاصيل الخدمة',
            ],
            [
                'text' => 'المشكلة موجودة منذ يومين تقريباً.',
                'sender_type' => 'customer',
                'topic' => 'تفاصيل الخدمة',
            ],
            [
                'text' => 'هل سبق أن تم إصلاح هذا العطل؟',
                'sender_type' => 'worker',
                'topic' => 'تفاصيل الخدمة',
            ],
            [
                'text' => 'لا، هذه أول مرة يحدث فيها هذا العطل.',
                'sender_type' => 'customer',
                'topic' => 'تفاصيل الخدمة',
            ],

            // تأكيد التنفيذ
            [
                'text' => 'تم الانتهاء من العمل بنجاح.',
                'sender_type' => 'worker',
                'topic' => 'تأكيد التنفيذ',
            ],
            [
                'text' => 'شكراً لك، تم حل المشكلة.',
                'sender_type' => 'customer',
                'topic' => 'تأكيد التنفيذ',
            ],
            [
                'text' => 'هل كل شيء يعمل بشكل جيد الآن؟',
                'sender_type' => 'worker',
                'topic' => 'تأكيد التنفيذ',
            ],
            [
                'text' => 'نعم، الخدمة ممتازة.',
                'sender_type' => 'customer',
                'topic' => 'تأكيد التنفيذ',
            ],

            // متابعة الطلب
            [
                'text' => 'ما هو وضع الطلب حالياً؟',
                'sender_type' => 'customer',
                'topic' => 'متابعة الطلب',
            ],
            [
                'text' => 'أنا في الطريق إلى الموقع.',
                'sender_type' => 'worker',
                'topic' => 'متابعة الطلب',
            ],
            [
                'text' => 'سأصل خلال 15 دقيقة.',
                'sender_type' => 'worker',
                'topic' => 'متابعة الطلب',
            ],
            [
                'text' => 'تم الوصول إلى الموقع.',
                'sender_type' => 'worker',
                'topic' => 'متابعة الطلب',
            ],

            // تعديل الطلب
            [
                'text' => 'أرغب بإضافة خدمة أخرى إلى الطلب.',
                'sender_type' => 'customer',
                'topic' => 'تعديل الطلب',
            ],
            [
                'text' => 'تم تعديل الطلب بنجاح.',
                'sender_type' => 'worker',
                'topic' => 'تعديل الطلب',
            ],
            [
                'text' => 'يرجى توضيح التعديل المطلوب.',
                'sender_type' => 'worker',
                'topic' => 'تعديل الطلب',
            ],
            [
                'text' => 'تم تحديث تفاصيل الطلب.',
                'sender_type' => 'customer',
                'topic' => 'تعديل الطلب',
            ],

        ];
        foreach ($templates as $template) {

                    $topic = MessageTopic::where(
                        'topic',
                        $template['topic']
                    )->first();

                    if (!$topic) {
                        continue;
                    }

                    MessageTemplate::create([
                        'text' => $template['text'],
                        'sender_type' => $template['sender_type'],
                        'topic_id' => $topic->id,
                    ]);
                }
      }
}