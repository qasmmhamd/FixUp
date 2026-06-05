<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserDeviceSeeder extends Seeder
{
    public function run(): void
    {

    
        foreach (User::all() as $user) {

            UserDevice::create([

                'user_id' => $user->id,

                'fcm_token' =>
                    Str::random(180),

                'device_type' =>
                    fake()->randomElement([
                        'android',
                        'ios',
                        'web'
                    ]),
            ]);
        }
    }
}