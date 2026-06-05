<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\AreaAddress;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $streets = [

            'شارع الثورة',
            'شارع بغداد',
            'شارع خالد بن الوليد',
            'شارع الجلاء',
            'شارع الحمرا',
            'شارع المزة',
            'شارع فلسطين',
            'شارع نسرين',
            'شارع المدارس',
            'شارع الجامع',

        ];

        foreach (User::all() as $user) {

            Address::create([

                'user_id' => $user->id,

                'area_address_id' =>
                    AreaAddress::inRandomOrder()->first()->id,

                'detailed_address' =>
                    fake()->randomElement($streets)
                    .' بناء '.rand(1,50)
                    .' طابق '.rand(1,8),

                'latitude' =>
                    fake()->latitude(33.40,33.65),

                'longitude' =>
                    fake()->longitude(36.15,36.45),

            ]);
        }
    }
}