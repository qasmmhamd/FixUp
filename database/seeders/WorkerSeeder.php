<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Worker;
use App\Models\Career;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        $abouts = [
            'خبرة واسعة في تنفيذ أعمال الصيانة المنزلية.',
            'أعمل في هذا المجال منذ سنوات طويلة.',
            'متخصص في تقديم خدمات احترافية وسريعة.',
            'أهتم بالدقة وجودة العمل.',
            'أمتلك خبرة في المشاريع السكنية والتجارية.',
        ];

        $workers = User::where('role', 'worker')->get();

        foreach ($workers as $user) {

            Worker::create([
                'user_id' => $user->id,
                'career_id' => Career::inRandomOrder()->first()->id,
                'about' => fake()->randomElement($abouts),
                'status' => fake()->randomElement([
                    'active',
                    'active',
                    'active',
                    'waiting'
                ]),
                'years_experience' => rand(1, 20),
            ]);
        }
    }
}