<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateWorkerRequest;
use App\Services\WorkerService;

class ManagingWorkersController extends Controller
{       use AuthorizesRequests;

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

    public function delete(Worker $worker)
    {
        $this->authorize('delete', $worker);

        $worker->delete();

        return response()->json([
            "message" => "Worker deleted successfully"
        ]);
}
}
