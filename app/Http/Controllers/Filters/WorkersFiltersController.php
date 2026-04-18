<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Http\Resources\WorkerResource;

class WorkersFiltersController extends Controller
{
     public function index(Request $request)
    {
        $workers = Worker::query()
            ->when($request->status, fn($q) => $q->status($request->status))
            ->paginate(10);

        return response()->json($workers);
    }
    
}
