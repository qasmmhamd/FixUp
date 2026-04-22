<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @class WorkersFiltersController
 *
 * Handles filtering and searching of workers based on various criteria.
 * This controller provides endpoints for administrators to filter workers
 * by status, career, location, and other attributes.
 */
class WorkersFiltersController extends Controller
{
    /**
     * Display a paginated list of workers with optional filtering.
     *
     * @param  Request  $request  The HTTP request containing filter parameters
     * @return JsonResponse Returns paginated workers data
     *
     * Query Parameters:
     * - status: Filter workers by status (active, inactive, pending)
     * - career_id: Filter by career ID
     * - location: Filter by location
     *
     * @example GET /api/admin/workers/filters?status=active
     */
    public function index(Request $request)
    {
        $workers = Worker::query()
            ->when($request->status, fn ($q) => $q->status($request->status))
            ->paginate(10);

        return response()->json($workers);
    }
}
