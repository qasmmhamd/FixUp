<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * @class NotificationController
 *
 * Handles user notifications retrieval and management.
 * Provides endpoints for listing notifications, filtering unread ones,
 * and marking notifications as read.
 *
 * This controller operates on in-app notifications stored in the database
 * and assumes each notification is linked to an authenticated user.
 */
class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Notification::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    /**
     * Get unread notifications for the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function unread()
    {
        return Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    /**
     * Mark a specific notification as read.
     *
     * @param int $id Notification ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(int $id)
    {
        $notification = Notification::findOrFail($id);

        // Security check: ensure user owns the notification
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
}