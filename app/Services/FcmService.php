<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function send($fcmToken, $title, $body, $data = [])
    {
        try {

            Log::info('FCM START', [
                'token' => $fcmToken,
                'title' => $title,
            ]);

            if (empty($fcmToken)) {
                Log::warning('FCM SKIPPED: empty token');
                return null;
            }

            // 🔥 جلب Access Token من Firebase
            $accessToken = (new FirebaseService())->getAccessToken();

            if (!$accessToken) {
                Log::error('FCM ERROR: missing access token');
                return null;
            }

            Log::info('FCM ACCESS TOKEN OK');

            // 🔥 تحويل data إلى string (مطلوب من FCM HTTP v1)
            $formattedData = [];
            foreach ($data as $key => $value) {
                $formattedData[$key] = (string) $value;
            }

            // 🔥 طلب FCM
            $response = Http::timeout(10)
                ->withToken($accessToken)
                ->post(
                    "https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send",
                    [
                        "message" => [
                            "token" => $fcmToken,
                            "notification" => [
                                "title" => $title,
                                "body" => $body,
                            ],
                            "data" => $formattedData,
                        ]
                    ]
                );

            // 🔥 تسجيل الرد الكامل
            Log::info('FCM RESPONSE', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if (!$response->successful()) {
                Log::error('FCM FAILED', [
                    'response' => $response->body(),
                ]);
            }

            return $response->json();

        } catch (\Throwable $e) {

            Log::error('FCM EXCEPTION', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return null;
        }
    }
}