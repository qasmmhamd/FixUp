<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCareerRequest;
use App\Http\Requests\UpdateCareerRequest;
use App\Http\Resources\CareerResource;
use App\Models\Career;

/**
 * @class ManagingCareersController
 *
 * Handles admin CRUD operations for careers (professions) in the system.
 *
 * This controller is part of the Admin Dashboard and is responsible for:
 * - Listing all careers
 * - Creating new careers
 * - Viewing single career details
 * - Updating careers
 * - Deleting careers
 *
 * All responses are formatted using CareerResource for consistency.
 */
class ManagingCareersController extends Controller
{
    /**
     * Get all careers ordered by latest.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CareerResource::collection(
            Career::latest()->get()
        );
    }

    /**
     * Create a new career.
     *
     * @param StoreCareerRequest $request
     * @return CareerResource
     */
    public function store(StoreCareerRequest $request)
    {
        $career = Career::create(
            $request->validated()
        );

        return new CareerResource($career);
    }

    /**
     * Show a single career.
     *
     * @param Career $career
     * @return CareerResource
     */
    public function show(Career $career)
    {
        return new CareerResource($career);
    }

    /**
     * Update an existing career.
     *
     * @param UpdateCareerRequest $request
     * @param Career $career
     * @return CareerResource
     */
    public function update(UpdateCareerRequest $request, Career $career)
    {
        $career->update(
            $request->validated()
        );

        return new CareerResource($career);
    }

    /**
     * Delete a career from system.
     *
     * @param Career $career
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Career $career)
    {
        $career->delete();

        return response()->json([
            'message' => 'Career deleted successfully',
        ]);
    }
}