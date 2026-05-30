<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

/**
 * @class ManagingWorkersServiesController
 *
 * Handles admin CRUD operations for services in the system.
 *
 * Services are linked to careers and define what type of work
 * a worker can perform (e.g. plumbing, electrical repair, etc.).
 *
 * This controller provides:
 * - Listing services with optional filtering by career
 * - Creating new services
 * - Viewing single service
 * - Updating services
 * - Deleting services
 */
class ManagingWorkersServiesController extends Controller
{
    /**
     * List all services with optional filtering by career.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Service::with('career');

        // Filter services by career if provided
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

    /**
     * Create a new service.
     *
     * @param StoreServiceRequest $request
     * @return ServiceResource
     */
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create(
            $request->validated()
        );

        return new ServiceResource($service);
    }

    /**
     * Show a specific service.
     *
     * @param Service $service
     * @return ServiceResource
     */
    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    /**
     * Update an existing service.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     * @return ServiceResource
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update(
            $request->validated()
        );

        return new ServiceResource($service);
    }

    /**
     * Delete a service from the system.
     *
     * @param Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully',
        ]);
    }
}