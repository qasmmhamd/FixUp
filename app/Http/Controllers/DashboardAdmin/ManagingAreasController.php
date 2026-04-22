<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAreaAddressRequest;
use App\Http\Resources\AreaAddressResource;
use App\Models\AreaAddress;

/**
 * @class ManagingAreasController
 * 
 * Handles CRUD operations for service areas management.
 * This controller provides endpoints for administrators to manage
 * geographical areas where services are available.
 */
class ManagingAreasController extends Controller
{
    /**
     * Display a paginated list of all service areas.
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AreaAddressResource::collection(AreaAddress::latest()->paginate(10));
    }

    /**
     * Store a new service area in the system.
     * 
     * @param StoreAreaAddressRequest $request The validated area creation request
     * @return AreaAddressResource The newly created area resource
     */
    public function store(StoreAreaAddressRequest $request)
    {
        $area = AreaAddress::create($request->validated());

        return new AreaAddressResource($area);
    }

    /**
     * Remove the specified service area from storage.
     * 
     * @param AreaAddress $area The area to delete
     * @return \Illuminate\Http\JsonResponse Deletion confirmation
     */
    public function destroy(AreaAddress $area)
    {
        $area->delete();

        return response()->json([
            'message' => 'Area deleted successfully',
        ]);
    }
}
