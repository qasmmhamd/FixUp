<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Career;
use App\Models\Service;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [

            'المكيف لا يعمل ويحتاج إلى صيانة.',
            'يوجد تسريب مياه في الحمام.',
            'تركيب إنارة جديدة للمنزل.',
            'تصليح باب خشبي مكسور.',
            'تركيب كاميرات مراقبة.',
            'تنظيف خزان المياه.',
            'ضعف في شبكة الإنترنت المنزلية.',
            'تركيب ألواح طاقة شمسية.',
            'تركيب سيراميك للمطبخ.',
            'صيانة لوحة الكهرباء الرئيسية.',
            'صيانة سخان المياه.',
            'تصليح نافذة ألمنيوم.',
            'إصلاح تسرب في المطبخ.',
            'تركيب مروحة سقف.',
            'صيانة مكيف سبليت.',
        ];

        $customers = User::where('role', 'customer')
            ->with('address')
            ->get();

        $statuses = [

            'pending',
            'accepted',
            'completed',
            'cancelled',
            'completion_requested',

        ];

        for ($i = 1; $i <= 300; $i++) {

            $customer = $customers->random();

            $career = Career::inRandomOrder()->first();

            $order = Order::create([

                'user_id' => $customer->id,

                'description' =>
                    fake()->randomElement($descriptions),

                'priority' =>
                    fake()->boolean(20),

                'status' =>
                    fake()->randomElement($statuses),

                'expires_at' =>
                    now()->addDays(rand(1,7)),

                'address_id' =>
                    $customer->address?->id,

                'career_id' =>
                    $career->id,

                'scheduled_at' =>
                    now()->addDays(rand(1,14)),
            ]);

            $services = Service::where(
                'career_id',
                $career->id
            )->get();

            if ($services->count()) {

                $order->services()->attach(

                    $services
                        ->random(
                            rand(
                                1,
                                min(
                                    3,
                                    $services->count()
                                )
                            )
                        )
                        ->pluck('id')
                        ->toArray()

                );
            }
        }
    }
}