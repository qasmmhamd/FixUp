<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountUpgradeController extends Controller
{
    
    public function updatedata(Request $request){
        $ViewData = $request->validate([
            "name" => "string|max:255",
            "phone" => "digits:10",
            "address" => "string|max:255",
            "profile_picture"=>"image|mimes:jpg,jpeg,png",
        ]);
         $user = $request->user();
         if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('images', 'public');
            $user = $request->user();
            $user->profile_picture = $imagePath;
        }
        $user->update($ViewData);
    
       return response()->json([
        'message' => 'Account upgraded successfully',
        'user' => $request->user()
        ]);
    }
}
