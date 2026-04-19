<?php
namespace App\Services;

use App\Models\Worker;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkerService
{
    // CREATE
    public function create(array $data, $files, $user)
    {
        return DB::transaction(function () use ($data, $files, $user) {

            $worker = Worker::create([
                "user_id" => $user->id,
                "status" => $data["status"] ?? "waiting",
                "career_id" => $data["career_id"],
                "about" => $data["about"] ?? null,
                "years_experience" => $data["years_experience"] ?? null,
            ]);
              $user->role = 'worker';
                 $user->save();
            if (!empty($data['services'])) {
                $worker->services()->sync($data['services']);
            }

            if (!empty($files['images'])) {
                $this->uploadImages($worker, $files['images'], $user);
            }

            return $worker->load('services', 'images');
        });
    }

    // UPDATE
    public function update(Worker $worker, array $data, $files, $user)
    {
        return DB::transaction(function () use ($worker, $data, $files, $user) {

            $worker->update([
                "career_id" => $data["career_id"] ?? $worker->career_id,
                "about" => $data["about"] ?? $worker->about,
                "years_experience" => $data["years_experience"] ?? $worker->years_experience,
                "status" => $data["status"] ?? $worker->status,
            ]);

            


            if (isset($data['services'])) {
                $worker->services()->sync($data['services']);
            }

            // حذف صور
            if (!empty($data['delete_images'])) {
                $this->deleteImages($worker, $data['delete_images']);
            }

            // رفع صور جديدة
            if (!empty($files['images'])) {
                $this->uploadImages($worker, $files['images'], $user);
            }

            return $worker->load('services', 'images');
        });
    }

    private function uploadImages($worker, $images, $user)
    {
        $data = [];

        foreach ($images as $file) {
            $path = $file->store('workers', 'public');

            $data[] = [
                "user_id" => $user->id,
                "worker_id" => $worker->id,
                "path" => $path,
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }

        Image::insert($data);
    }

    private function deleteImages($worker, $ids)
    {
        $images = Image::whereIn('id', $ids)
            ->where('worker_id', $worker->id)
            ->get();

        foreach ($images as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }
    }
}