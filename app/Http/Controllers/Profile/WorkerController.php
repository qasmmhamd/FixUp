<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;

/**
 * @class WorkerController
 * 
 * Handles worker profile management operations.
 * This controller provides endpoints for workers to manage their profiles,
 * view their information, and handle worker-specific functionality.
 */
class WorkerController extends Controller
{
    /**
     * Display the worker's profile information.
     * 
     * @param Request $request The HTTP request
     * @return WorkerResource The worker profile resource
     */
    public function show(Request $request)
    {
        $worker = $request->user()->worker;
        
        return new WorkerResource($worker->load([
            'user',
            'career',
            'services',
            'images'
        ]));
    }

    /**
     * Update the worker's profile information.
     * 
     * @param Request $request The HTTP request with updated data
     * @return \Illuminate\Http\JsonResponse Updated worker profile
     */
    public function update(Request $request)
    {
        $worker = $request->user()->worker;
        
        $validated = $request->validate([
            'about' => 'nullable|string|max:1000',
            'years_experience' => 'nullable|integer|min:0|max:50',
            'status' => 'sometimes|in:active,inactive,pending',
        ]);
        
        $worker->update($validated);
        
        return response()->json([
            'message' => 'Worker profile updated successfully',
            'worker' => new WorkerResource($worker)
        ]);
    }
}
