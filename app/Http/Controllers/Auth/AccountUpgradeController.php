<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AccountUpgradeController extends Controller
{
    public function updatedata(Request $request)
    {
        $ViewData = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'digits:10',
            'profile_picture' => 'image|mimes:jpg,jpeg,png',
        ]);
        $user = $request->user();
        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('images', 'public');
            $user = $request->user();
            $user->profile_picture = $imagePath;
        }
        $user->update($ViewData);
        $request->validate([
            'city' => 'required|string',
            'street' => 'required|string',
        ]);

        // إنشاء العنوان
        $address = Address::create([
            'user_id' => $request->user()->id, // المستخدم الحالي
            'city' => $request->city,
            'street' => $request->street,
        ]);

        return response()->json([
            'message' => 'Account upgraded successfully',
            'user' => $request->user(),
        ]);
    }
}
