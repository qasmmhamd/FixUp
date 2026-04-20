<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UpdateUserService;

class UserController extends Controller
{
   
   public function update(UpdateUserRequest $request, UpdateUserService $service)
{
    $user = $service->update($request->user(), $request);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
}

}
