<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\UserService;


class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request, UserService $service)
    {  // dd($request->validated());
          $user = $service->register($request->validated());

         return response()->json([
             'message' => 'User created successfully',
             'user' => $user
         ]);
    
        event(new Registered($user));

        //Auth::login($user);

        return response()->noContent();
    }
}
