<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserProfileResource;
use App\Services\UpdateUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UserController
 *
 * Handles authenticated user profile operations:
 * - Viewing profile
 * - Updating profile information
 */
class UserController extends Controller
{
    /**
     * Display authenticated user profile.
     *
     * @param Request $request
     * @return UserProfileResource
     */
    public function show(Request $request): UserProfileResource
    {
        /*
        |--------------------------------------------------------------------------
        | Load User Profile
        |--------------------------------------------------------------------------
        */

        return new UserProfileResource(
            $request->user()->loadMissing('address.areaAddress')
        );
    }

    /**
     * Update authenticated user profile.
     *
     * @param UpdateUserRequest $request
     * @param UpdateUserService $service
     * @return JsonResponse
     */
    public function update(
        UpdateUserRequest $request,
        UpdateUserService $service
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Execute Update Service
        |--------------------------------------------------------------------------
        */

        $user = $service->update(
            $request->user(),
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'User updated successfully',
            'user'    => $user,
        ]);
    }
}