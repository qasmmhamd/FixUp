<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use Illuminate\Http\JsonResponse;

/**
 * @class DeviceController
 *
 * Handles registration of user devices for push notifications (FCM).
 * Stores and updates Firebase Cloud Messaging tokens associated with users.
 *
 * Each user can have multiple devices, and each device is uniquely identified
 * by its FCM token.
 */
class DeviceController extends Controller
{
    /**
     * Register or update a user's device FCM token.
     *
     * This endpoint ensures that the user's device is stored for later
     * push notifications via Firebase Cloud Messaging.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        // Ensure user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Validate incoming request data
        $validated = $request->validate([
            'fcm_token'   => 'required|string',
            'device_type' => 'nullable|string',
        ]);

        // Store or update device record
        $device = UserDevice::updateOrCreate(
            [
                'user_id'   => $user->id,
                'fcm_token' => $validated['fcm_token'],
            ],
            [
                'device_type' => $validated['device_type'] ?? 'unknown',
            ]
        );

        return response()->json([
            'message' => 'Device saved successfully',
            'data'    => $device,
        ]);
    }
}