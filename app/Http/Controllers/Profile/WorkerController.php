<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use App\Http\Requests\UpdateWorkerProfileRequest;
use App\Services\WorkerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

/**
 * @class WorkerController
 * 
 * Handles worker profile management operations.
 * This controller provides endpoints for workers to manage their profiles,
 * view their information, and handle worker-specific functionality.
 */
class WorkerController extends Controller
{
   



    public function __construct(
        private WorkerService $workerService
    ) {}

    /**
     * تحديث بيانات العامل
     */
    public function update(UpdateWorkerProfileRequest $request): JsonResponse
    {
        $user = Auth::user();

        // تأكد أن المستخدم عنده Worker
        $worker = $user->worker;

        if (! $worker) {
            return response()->json([
                'message' => 'Worker profile not found'
            ], 404);
        }

        // البيانات
        $data = $request->validated();

        // الملفات (images)
        $files = [
            'images' => $request->file('images')
        ];

        $updatedWorker = $this->workerService->update(
            $worker,
            $data,
            $files,
            $user
        );

        return response()->json([
            'message' => 'Worker updated successfully',
            'data' => $updatedWorker
        ]);
    }
}

