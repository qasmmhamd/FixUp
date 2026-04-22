<?php

namespace App\Services;

use App\Models\Image;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @class WorkerService
 * 
 * Handles business logic for worker profile management in the FixUp system.
 * This service manages worker creation, updates, image uploads, and related
 * operations while maintaining data integrity through database transactions.
 */
class WorkerService
{
    /**
     * Create a new worker profile with associated data.
     * 
     * @param array $data The worker profile data
     * @param array $files Uploaded files (images)
     * @param User $user The user creating the worker profile
     * @return Worker The created worker with loaded relationships
     */
    public function create(array $data, $files, $user)
    {
        return DB::transaction(function () use ($data, $files, $user) {

            $worker = Worker::create([
                'user_id' => $user->id,
                'status' => $data['status'] ?? 'waiting',
                'career_id' => $data['career_id'],
                'about' => $data['about'] ?? null,
                'years_experience' => $data['years_experience'] ?? null,
            ]);
            $user->role = 'worker';
            $user->save();
            if (! empty($data['services'])) {
                $worker->services()->sync($data['services']);
            }

            if (! empty($files['images'])) {
                $this->uploadImages($worker, $files['images'], $user);
            }

            return $worker->load('services', 'images');
        });
    }

    /**
     * Update an existing worker profile.
     * 
     * @param Worker $worker The worker to update
     * @param array $data The updated worker data
     * @param array $files Uploaded files (new images)
     * @param User $user The user performing the update
     * @return Worker The updated worker with loaded relationships
     */
    public function update(Worker $worker, array $data, $files, $user)
    {
        return DB::transaction(function () use ($worker, $data, $files, $user) {

            $worker->update([
                'career_id' => $data['career_id'] ?? $worker->career_id,
                'about' => $data['about'] ?? $worker->about,
                'years_experience' => $data['years_experience'] ?? $worker->years_experience,
                'status' => $data['status'] ?? $worker->status,
            ]);

            if (isset($data['services'])) {
                $worker->services()->sync($data['services']);
            }

            // حذف صور
            if (! empty($data['delete_images'])) {
                $this->deleteImages($worker, $data['delete_images']);
            }

            // رفع صور جديدة
            if (! empty($files['images'])) {
                $this->uploadImages($worker, $files['images'], $user);
            }

            return $worker->load('services', 'images');
        });
    }

    /**
     * Upload and store images for a worker profile.
     * 
     * @param Worker $worker The worker profile
     * @param array $images The uploaded image files
     * @param User $user The user uploading the images
     * @return void
     */
    private function uploadImages($worker, $images, $user)
    {
        $data = [];

        foreach ($images as $file) {
            $path = $file->store('workers', 'public');

            $data[] = [
                'user_id' => $user->id,
                'worker_id' => $worker->id,
                'path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Image::insert($data);
    }

    /**
     * Delete worker images from storage and database.
     * 
     * @param Worker $worker The worker profile
     * @param array $ids The image IDs to delete
     * @return void
     */
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
