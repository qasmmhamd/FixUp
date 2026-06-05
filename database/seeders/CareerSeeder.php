<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $careers = [
            'كهربائي',
            'سباك',
            'نجار',
            'فني تكييف',
            'دهان',
            'عامل تنظيف',
            'فني إنترنت',
            'فني كاميرات',
            'حداد',
            'فني طاقة شمسية',
        ];

        foreach ($careers as $career) {
            Career::firstOrCreate([
                'name' => $career
            ]);
        }
    }
}