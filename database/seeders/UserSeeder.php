<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create('ar_SA');

        User::updateOrCreate(
            ['email' => 'admin@fixup.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('2004Qasm'),
                'role' => 'admin',
                'phone_number' => '0999999999',
                'is_active' => true,
            ]
        );

        for ($i = 1; $i <= 100; $i++) {

            User::create([
                'name' => $faker->unique()->name(),
                'email' => "customer{$i}@fixup.com",
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'phone_number' => '09'.rand(10000000,99999999),
                'birth_date' => $faker->date(),
                'is_active' => true,
            ]);
        }

        for ($i = 1; $i <= 50; $i++) {

            User::create([
                'name' => $faker->unique()->name(),
                'email' => "worker{$i}@fixup.com",
                'password' => Hash::make('12345678'),
                'role' => 'worker',
                'phone_number' => '09'.rand(10000000,99999999),
                'birth_date' => $faker->date(),
                'is_active' => true,
            ]);
        }
    }
}