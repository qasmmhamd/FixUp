<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\JobFeeRule;
use Illuminate\Database\Seeder;

class JobFeeRuleSeeder extends Seeder
{
    public function run(): void
    {
        $fees = [
            'كهربائي' => 150,
            'سباك' => 150,
            'نجار' => 120,
            'فني تكييف' => 200,
            'دهان' => 100,
            'عامل تنظيف' => 80,
            'فني إنترنت' => 120,
            'فني كاميرات' => 150,
            'حداد' => 150,
            'فني ألمنيوم' => 150,
            'عامل بلاط' => 120,
            'فني طاقة شمسية' => 250,
        ];

        foreach ($fees as $careerName => $fee) {

            $career = Career::where('name', $careerName)->first();

            if (!$career) {
                continue;
            }

            JobFeeRule::create([
                'career_id' => $career->id,
                'fee' => $fee,
                'is_active' => true,
            ]);
        }
    }
}