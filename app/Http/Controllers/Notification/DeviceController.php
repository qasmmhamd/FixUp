<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'nullable|string',
        ]);

        \App\Models\UserDevice::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'fcm_token' => $request->fcm_token,
            ],
            [
                'device_type' => $request->device_type,
            ]
        );

        return response()->json([
            'message' => 'Device saved successfully'
        ]);
    }
}
