<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UpdateUserService;
use App\Http\Resources\UserProfileResource;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
  public function show(Request $request)
{
   return new UserProfileResource(
        $request->user()->loadMissing('address.areaAddress')
    );
}
   public function update(UpdateUserRequest $request, UpdateUserService $service)
{
    $user = $service->update($request->user(), $request);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
}

}
