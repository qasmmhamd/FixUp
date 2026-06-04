<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkerProfileRequest;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use App\Services\WorkerService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\RatingStatsResource;
use Illuminate\Support\Facades\Auth;
use Exception;
use Throwable;

/**
 * Class WorkerController
 *
 * Handles worker profile operations including:
 * - Listing workers
 * - Updating authenticated worker profile
 */
class WorkerController extends Controller
{
    /**
     * Worker service instance.
     *
     * @var WorkerService
     */
    public function __construct(
        private WorkerService $workerService
    ) {}

    /**
     * Get all workers with related data.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch Workers
        |--------------------------------------------------------------------------
        */

        $workers = Worker::with([
                'user',
                'career',
                'images'
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Response (Resource Collection)
        |--------------------------------------------------------------------------
        */

        return WorkerResource::collection($workers);
    }

    /**
     * Update authenticated worker profile.
     *
     * @param UpdateWorkerProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateWorkerProfileRequest $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Get Authenticated User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Resolve Worker Profile
        |--------------------------------------------------------------------------
        */

        $worker = $user->worker;

        if (! $worker) {
            return response()->json([
                'message' => 'Worker profile not found'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Prepare Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();

        $files = [
            'images' => $request->file('images')
        ];

        /*
        |--------------------------------------------------------------------------
        | Execute Update
        |--------------------------------------------------------------------------
        */

        $updatedWorker = $this->workerService->update(
            $worker,
            $data,
            $files,
            $user
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Worker updated successfully',
            'data'    => $updatedWorker
        ]);
    }
    /**
     * Display the worker's rating statistics.
     */
    public function show(int $workerId): RatingStatsResource
    {
        $ratingStats = $this->workerService->getWorkerRating($workerId);

        return new RatingStatsResource($ratingStats);
    }
 
     /**
      * Get worker job fee for the authenticated worker.
      *
      * @return JsonResponse
      */
     public function getFee(): JsonResponse
    {
      
    $user = Auth::user();

    $worker = $user->worker;

    if (! $worker) {
        return response()->json([
            'message' => 'Worker profile not found'
        ], 404);
    }

    $fee = $this->workerService->getWorkerFee($worker->id);

    return response()->json([
        'message' => 'Worker fee retrieved successfully',
        'data' => $fee
    ]);
    
    }
}