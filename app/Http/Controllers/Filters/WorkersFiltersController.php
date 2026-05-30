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
 * Allows administrators to filter workers by status and
 * perform a unified search using worker name or phone number.
 *
 * @param  Request  $request  The HTTP request containing filter parameters.
 * @return JsonResponse Returns a paginated collection of workers.
 *
 * Query Parameters:
 * - status: Filter workers by status (active, blocked, waiting).
 * - search: Search by worker name or phone number.
 *
 * Examples:
 * GET /api/admin/workers/filters
 * GET /api/admin/workers/filters?status=active
 * GET /api/admin/workers/filters?search=Ahmed
 * GET /api/admin/workers/filters?search=07701234567
 * GET /api/admin/workers/filters?status=active&search=Ahmed
 */
   public function index(Request $request): JsonResponse
{
    $workers = Worker::query()

        // status filter (direct column)
        ->when($request->status, fn ($q) =>
            $q->where('status', $request->status)
        )

        // user filters grouped (name + phone)
        ->when($request->filled(['name', 'phone']), function ($q) use ($request) {
            $q->whereHas('user', function ($user) use ($request) {
                $user->when($request->name, fn ($u) =>
                        $u->where('name', 'like', "%{$request->name}%")
                )
                ->when($request->phone, fn ($u) =>
                        $u->where('phone_number', 'like', "%{$request->phone}%")
                );
            });
        })

        // fallback if only one is sent
        ->when(!$request->filled(['name', 'phone']) && $request->filled('name'), function ($q) use ($request) {
            $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$request->name}%")
            );
        })

        ->when(!$request->filled(['name', 'phone']) && $request->filled('phone'), function ($q) use ($request) {
            $q->whereHas('user', fn ($u) =>
                $u->where('phone_number', 'like', "%{$request->phone}%")
            );
        })

        ->with(['user.address', 'career', 'services', 'images'])
        ->paginate(10);

    return response()->json($workers);
}
}
