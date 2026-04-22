<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @class RegisteredUserController
 * 
 * Handles user registration operations for the FixUp application.
 * This controller manages the registration of new customer accounts,
 * including validation, user creation, and event triggering.
 */
class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param RegisterUserRequest $request The validated registration request
     * @param UserService $service The user service for business logic
     * @return \Illuminate\Http\JsonResponse Registration response
     * @throws ValidationException If validation fails
     */
    public function store(RegisterUserRequest $request, UserService $service)
    { 
        $user = $service->register($request->validated());

        // Trigger registration event for email verification
        event(new Registered($user));

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }
}
