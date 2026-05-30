<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;

/**
 * Class NotificationService
 *
 * Unified notification system responsible for:
 * 1. Storing in-app notifications in database
 * 2. Sending push notifications via FCM
 * 3. Managing multi-device delivery per user
 *
 * Architecture:
 * - DB layer: notifications table (persistent)
 * - Push layer: Firebase Cloud Messaging (real-time)
 * - Device layer: user_devices (FCM tokens per user)
 */
class NotificationService
{
    /**
     * Send a complete notification to a user.
     *
     * This includes:
     * - Database persistence (in-app notification)
     * - Firebase push notification (multi-device)
     *
     * @param mixed  $user   Target user model
     * @param string $title  Notification title
     * @param string $body   Notification body
     * @param string $type   Notification category/type
     * @param array  $data   Optional payload data
     * @return void
     */
    public function send(
        $user,
        $title,
        $body,
        $type,
        $data = []
    ) {
        /**
         * Debug trace (development only)
         */
        Log::info('NotificationService triggered');

        /**
         * Validate user existence
         */
        if (!$user) {
            Log::warning('Notification skipped: user is null');
            return;
        }

        /**
         * Persist notification in database (in-app system)
         */
        Notification::create([
            'user_id' => $user->id,
            'title'   => $title,
            'body'    => $body,
            'type'    => $type,
            'data'    => $data,
        ]);

        Log::info('Notification stored', [
            'user_id' => $user->id,
            'type'    => $type,
        ]);

        /**
         * Retrieve all active FCM tokens for user devices
         */
        $tokens = UserDevice::where('user_id', $user->id)
            ->pluck('fcm_token')
            ->filter();

        /**
         * If user has no registered devices → skip push
         */
        if ($tokens->isEmpty()) {
            Log::warning('FCM skipped: no tokens found', [
                'user_id' => $user->id
            ]);
            return;
        }

        /**
         * Send push notification to all user devices
         */
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

                Log::info('FCM sent successfully', [
                    'user_id' => $user->id,
                    'token'   => $token
                ]);

            } catch (\Throwable $e) {

                /**
                 * Failure isolation per device
                 * (prevents one bad token from breaking system)
                 */
                Log::error('FCM send failed', [
                    'user_id' => $user->id,
                    'token'   => $token,
                    'error'   => $e->getMessage()
                ]);
            }
        }
    }
}