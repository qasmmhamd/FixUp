<?php
/*

namespace App\Services;

class NotificationService
{
    public function send($user, $title, $body, $type, $data = [])
    {
        // 🔹 تخزين في DB
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
        ]);

        // 🔹 إرسال FCM
        if (!empty($user->fcm_token)) {

            app(FcmService::class)->send(
                $user->fcm_token,
                $title,
                $body,
                array_merge($data, [
                    'type' => $type
                ])
            );
        }
    }
}|*/

namespace App\Services;

use App\Models\Notification;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function send($user, $title, $body, $type, $data = [])
    {Log::info('CALLED');
        if (!$user) {
            Log::warning('Notification skipped: user is null');
            return;
        }

        // 🔹 1. Store DB notification (IN-APP)
        Notification::create([
            'user_id' => $user->id,
            'title'   => $title,
            'body'    => $body,
            'type'    => $type,
            'data'    => $data, // ✅ بدون json_encode
        ]);

        Log::info('Notification stored', [
            'user_id' => $user->id,
            'type' => $type,
        ]);

        // 🔹 2. Get ALL user devices (IMPORTANT FIX)
        $tokens = UserDevice::where('user_id', $user->id)
            ->pluck('fcm_token')
            ->filter();

        if ($tokens->isEmpty()) {
            Log::warning('FCM skipped: no tokens found', [
                'user_id' => $user->id
            ]);
            return;
        }

        // 🔹 3. Send to all devices
        foreach ($tokens as $token) {

            try {
                app(FcmService::class)->send(
                    $token,
                    $title,
                    $body,
                    array_merge($data, [
                        'type' => $type
                    ])
                );

                Log::info('FCM sent', [
                    'user_id' => $user->id,
                    'token' => $token
                ]);

            } catch (\Throwable $e) {

                Log::error('FCM failed', [
                    'user_id' => $user->id,
                    'token' => $token,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}