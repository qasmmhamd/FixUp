<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateWorkerRequest;
use App\Services\WorkerService;
use App\Http\Resources\WorkerResource;

class ManagingWorkersController extends Controller
{       use AuthorizesRequests;
    public function index()
{
    $workers = Worker::with(['user', 'career'])->latest()->paginate(10);

    return WorkerResource::collection($workers);
}
        public function show(Worker $worker)
{
    return new WorkerResource($worker->load(['user', 'career']));
}

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
        "message" => "Worker updated successfully",
        "worker" => $worker
    ]);
    }

   

}
