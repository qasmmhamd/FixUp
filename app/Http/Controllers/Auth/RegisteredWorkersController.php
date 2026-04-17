<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Http\Requests\StoreWorkerRequest;
use App\Services\WorkerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegisteredWorkersController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreWorkerRequest $request, WorkerService $service)
{      
    $this->authorize('create', Worker::class);

    $worker = $service->create(
        $request->validated(),
        $request->allFiles(),
        $request->user()
    );
 
    return response()->json([
        "message" => "Worker created successfully",
        "worker" => $worker
    ], 201);
}
}
