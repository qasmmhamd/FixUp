<?php

namespace Database\Seeders;

use App\Models\Worker;
use App\Models\Service;
use Illuminate\Database\Seeder;

class WorkerServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Worker::all() as $worker) {

            $services = Service::where(
                'career_id',
                $worker->career_id
            )->get();

            if ($services->isEmpty()) {
                continue;
            }

            $worker->services()->sync(

                $services
                    ->random(
                        rand(
                            1,
                            min(
                                $services->count(),
                                3
                            )
                        )
                    )
                    ->pluck('id')
                    ->toArray()

            );
        }
    }
}