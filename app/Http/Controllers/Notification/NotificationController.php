<?php

namespace App\Http\Controllers\Notification;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // 📥 كل الإشعارات
    public function index()
    {
        return Auth::user()
            ->notifications()
            ->latest()
            ->get();
    }

    // 🔔 غير المقروءة فقط
    public function unread()
    {
        return Auth::user()
            ->notifications()
            ->unread()
            ->latest()
            ->get();
    }

    // ✔ تحديد إشعار كمقروء
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // حماية (لا يقرأ إشعار غيره)
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }
}
