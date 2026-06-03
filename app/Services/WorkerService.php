<?php

namespace App\Services;

use App\Models\Image;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Wallet;

/**
 * --------------------------------------------------------------------------
 * WorkerService
 * --------------------------------------------------------------------------
 *
 * Handles business logic for worker profile management in the FixUp system.
 * Responsible for creating and updating worker profiles, managing services,
 * and handling image uploads/deletions while ensuring data consistency.
 */
class WorkerService
{
    /**
     * ----------------------------------------------------------------------
     * Create Worker Profile
     * ----------------------------------------------------------------------
     *
     * Creates a new worker profile, assigns role to user, syncs services,
     * and uploads related images within a database transaction.
     *
     * @param array $data
     * @param array $files
     * @param User $user
     *
     * @return Worker
     */
    public function create(
        array $data,
        $files,
        $user
    ) {

        return DB::transaction(function () use (
            $data,
            $files,
            $user
        ) {
             /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Worker Creation
            |--------------------------------------------------------------------------
            */

            if (Worker::where('user_id', $user->id)->exists()) {
                throw new \Exception('Worker profile already exists for this user');
            }

            /*
            |--------------------------------------------------------------------------
            | Create Worker
            |--------------------------------------------------------------------------
            */

            $worker = Worker::create([
                'user_id'          => $user->id,
                'status'           => $data['status'] ?? 'waiting',
                'career_id'        => $data['career_id'],
                'about'            => $data['about'] ?? null,
                'years_experience' => $data['years_experience'] ?? null,

                
            ]);
            /*
            |--------------------------------------------------------------------------
            | Create Wallet For Worker
            |--------------------------------------------------------------------------
            */

            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'balance'       => 0,
                    'status'        => 'suspended',
                    'total_charged' => 0,
                    'total_spent'   => 0,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Assign Role To User
            |--------------------------------------------------------------------------
            */

            $user->role = 'worker';
            $user->save();

            /*
            |--------------------------------------------------------------------------
            | Sync Services
            |--------------------------------------------------------------------------
            */

            if (!empty($data['services'])) {

                $worker->services()->sync(
                    $data['services']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Upload Images
            |--------------------------------------------------------------------------
            */

            if (!empty($files['images'])) {

                $this->uploadImages(
                    $worker,
                    $files['images'],
                    $user
                );
            }

            return $worker->load(
                'services',
                'images'
            );
        });
    }

    /**
     * ----------------------------------------------------------------------
     * Update Worker Profile
     * ----------------------------------------------------------------------
     *
     * Updates worker details, services, and manages image lifecycle
     * (upload + deletion) within a transaction.
     *
     * @param Worker $worker
     * @param array $data
     * @param array $files
     * @param User $user
     *
     * @return Worker
     */
    public function update(
        Worker $worker,
        array $data,
        $files,
        $user
    ) {

        return DB::transaction(function () use (
            $worker,
            $data,
            $files,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update Worker Data
            |--------------------------------------------------------------------------
            */

            $worker->update([
                'career_id'        => $data['career_id'] ?? $worker->career_id,
                'about'            => $data['about'] ?? $worker->about,
                'years_experience' => $data['years_experience'] ?? $worker->years_experience,
                'status'           => $data['status'] ?? $worker->status,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Sync Services
            |--------------------------------------------------------------------------
            */

            if (isset($data['services'])) {

                $worker->services()->sync(
                    $data['services']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Delete Images
            |--------------------------------------------------------------------------
            */

            if (!empty($data['delete_images'])) {

                $this->deleteImages(
                    $worker,
                    $data['delete_images']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Upload New Images
            |--------------------------------------------------------------------------
            */

            if (!empty($files['images'])) {

                $this->uploadImages(
                    $worker,
                    $files['images'],
                    $user
                );
            }

            return $worker->load(
                'services',
                'images'
            );
        });
    }

    /**
     * ----------------------------------------------------------------------
     * Upload Worker Images
     * ----------------------------------------------------------------------
     *
     * Stores uploaded images in filesystem and inserts records in database.
     *
     * @param Worker $worker
     * @param array $images
     * @param User $user
     *
     * @return void
     */
    private function uploadImages(
        Worker $worker,
        $images,
        User $user
    ): void {

        $data = [];

        foreach ($images as $file) {

            $path = $file->store(
                'workers',
                'public'
            );

            $data[] = [
                'user_id'    => $user->id,
                'worker_id'  => $worker->id,
                'path'       => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Image::insert($data);
    }

    /**
     * ----------------------------------------------------------------------
     * Delete Worker Images
     * ----------------------------------------------------------------------
     *
     * Removes images from storage and database safely.
     *
     * @param Worker $worker
     * @param array $ids
     *
     * @return void
     */
    private function deleteImages(
        Worker $worker,
        array $ids
    ): void {

        $images = Image::whereIn(
                'id',
                $ids
            )
            ->where(
                'worker_id',
                $worker->id
            )
            ->get();

        foreach ($images as $img) {

            Storage::disk('public')->delete(
                $img->path
            );

            $img->delete();
        }
    }
    /**
     * Get a worker's average rating and total ratings.
     *
     * @param int $workerId
     * @return array
     */

    public function getWorkerRating(int $workerId): array
        {
            $worker = Worker::withAvg('ratings', 'rate')
                ->withCount('ratings')
                ->findOrFail($workerId);

            return [
                'average_rating' => $worker->ratings_avg_rate ?? 0,
                'ratings_count' => $worker->ratings_count,
            ];
        }
}