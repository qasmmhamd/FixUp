<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\UpdateServiceRequest;

class ManagingWorkersServiesController extends Controller
{
        public function index(Request $request)
    {
$query = Service::with('career');

    if ($request->filled('career_id')) {
        $request->validate([
            'career_id' => 'exists:careers,id',
        ]);

        $query->where('career_id', $request->career_id);
    }

    return ServiceResource::collection(
        $query->latest()->paginate(10)
    );
    }

    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());

        return new ServiceResource($service);
    }

    public function show(Service $service)
    {
        return new ServiceResource($service);
    }


    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return new ServiceResource($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully'
        ]);
    }

}
