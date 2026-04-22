<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class RoleMiddleware
 * 
 * Handles role-based authorization for the FixUp application.
 * This middleware ensures that only users with specific roles
 * can access certain routes and resources.
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request with role authorization.
     *
     * @param Request $request The incoming HTTP request
     * @param Closure $next The next middleware in the pipeline
     * @param string ...$roles The allowed roles for this route
     * @return Response The HTTP response or next middleware
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (! in_array(Auth::user()->role, $roles)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
