<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkerRequest;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use App\Services\WorkerService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;



/**
 * @class ManagingWorkersController
 *
 * Handles CRUD operations for worker management by administrators.
 * This controller provides endpoints for viewing, updating worker profiles
 * and managing their status in the FixUp system.
 */
class ManagingWorkersController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a paginated list of all workers.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $workers = Worker::with(['user.address.areaAddress', 'career', 'services', 'images'])->latest()->paginate(10);



        return response()->json($workers);
   }
   

    /**
     * Display a specific worker's details.
     *
     * @param  Worker  $worker  The worker instance
     * @return WorkerResource
     */
    public function show(Worker $worker)
    {
        return new WorkerResource($worker->load(['user.address.areaAddress', 'career', 'services', 'images']));
        
    }

    /**
     * Update a worker's profile information.
     *
     * @param  UpdateWorkerRequest  $request  The validated request data
     * @param  Worker  $worker  The worker instance to update
     * @param  WorkerService  $service  The worker service for business logic
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function update(UpdateWorkerRequest $request, Worker $worker, WorkerService $service)
    {    
        $this->authorize('update', $worker);

        $worker = $service->update(
            $worker,
            $request->validated(),
            $request->allFiles(),
            $request->user()
        );
        

        return response()->json([
            'message' => 'Worker updated successfully',
            'worker' => $worker,
        ]);
    }
}
