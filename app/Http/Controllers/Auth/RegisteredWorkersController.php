<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Models\Worker;
use App\Services\WorkerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @class RegisteredWorkersController
 * 
 * Handles worker registration operations for the FixUp application.
 * This controller manages the registration of new worker accounts,
 * including profile creation, file uploads, and authorization checks.
 */
class RegisteredWorkersController extends Controller
{
    use AuthorizesRequests;

    /**
     * Register a new worker in the system.
     * 
     * @param StoreWorkerRequest $request The validated worker registration request
     * @param WorkerService $service The worker service for business logic
     * @return \Illuminate\Http\JsonResponse Registration response with worker data
     * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized
     */
    public function store(StoreWorkerRequest $request, WorkerService $service)
    {
        $this->authorize('create', Worker::class);

        $worker = $service->create(
            $request->validated(),
            $request->allFiles(),
            $request->user()
        );

        return response()->json([
            'message' => 'Worker created successfully',
            'worker' => $worker,
        ], 201);
    }
}
