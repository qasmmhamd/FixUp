<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class FcmService
 *
 * Responsible for sending push notifications via Firebase Cloud Messaging (FCM HTTP v1 API).
 *
 * Responsibilities:
 * - Validates FCM tokens before sending
 * - Retrieves Firebase OAuth access token
 * - Formats payload according to FCM requirements
 * - Sends notifications to target devices
 * - Logs all responses and errors for monitoring
 *
 * Business Rules:
 * - FCM token must not be empty
 * - All data payload values must be strings (FCM requirement)
 * - Default fallback URL is "/customer/orders"
 *
 * External Dependencies:
 * - FirebaseService (for OAuth access token)
 * - Google FCM HTTP v1 API
 *
 * Failure Behavior:
 * - Returns null if token or access token is missing
 * - Logs all errors and exceptions without throwing
 *
 * @package App\Services
 */
class FcmService
{
    /**
     * Send push notification to a single device via Firebase Cloud Messaging.
     *
     * @param string $fcmToken Target device FCM token
     * @param string $title Notification title
     * @param string $body Notification body content
     * @param array<string, mixed> $data Optional custom data payload
     *
     * @return array|null Firebase response or null on failure
     */
    public function send(
        $fcmToken,
        $title,
        $body,
        $data = []
    ) {
        try {

            /*
            |--------------------------------------------------------------------------
            | Validate FCM Token
            |--------------------------------------------------------------------------
            */

            if (empty($fcmToken)) {

                Log::warning('FCM SKIPPED: empty token');
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Get Firebase Access Token
            |--------------------------------------------------------------------------
            */

            $accessToken = (new FirebaseService())->getAccessToken();

            if (!$accessToken) {

                Log::error('FCM ERROR: missing access token');
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Format Data Payload
            |--------------------------------------------------------------------------
            */

            $formattedData = [];

            foreach ((array) $data as $key => $value) {
                $formattedData[$key] = (string) $value;
            }

            $formattedData['title'] = (string) $title;
            $formattedData['body']  = (string) $body;

            $formattedData['url'] = isset($data['url'])
                ? (string) $data['url']
                : '/customer/orders';

            /*
            |--------------------------------------------------------------------------
            | Firebase Payload (HTTP v1)
            |--------------------------------------------------------------------------
            */

            $payload = [
                "message" => [
                    "token" => $fcmToken,

                    "notification" => [
                        "title" => (string) $title,
                        "body"  => (string) $body,
                    ],

                    "data" => $formattedData
                ]
            ];

            /*
            |--------------------------------------------------------------------------
            | Send Request
            |--------------------------------------------------------------------------
            */

            $response = Http::timeout(10)
                ->withToken($accessToken)
                ->post(
                    "https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send",
                    $payload
                );

            /*
            |--------------------------------------------------------------------------
            | Log Response
            |--------------------------------------------------------------------------
            */

            Log::info('FCM RESPONSE', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return $response->json();

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Exception Handling
            |--------------------------------------------------------------------------
            */

            Log::error('FCM EXCEPTION', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return null;
        }
    }
}