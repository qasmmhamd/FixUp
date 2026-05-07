<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function send($fcmToken, $title, $body, $data = [])
    {
        try {

            if (!$fcmToken) {
                Log::warning('FCM skipped: empty token');
                return null;
            }

            $accessToken = (new FirebaseService())->getAccessToken();

            // 🔥 تحويل البيانات إلى string (مهم جدًا)
            $formattedData = [];
            foreach ($data as $key => $value) {
                $formattedData[$key] = (string) $value;
            }

            $response = Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send", [
                    "message" => [
                        "token" => $fcmToken,
                        "notification" => [
                            "title" => $title,
                            "body" => $body
                        ],
                        "data" => $formattedData
                    ]
                ]);

            Log::info('FCM response', [
                'token' => $fcmToken,
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return $response->json();

        } catch (\Throwable $e) {

            Log::error('FCM send failed', [
                'error' => $e->getMessage(),
                'token' => $fcmToken
            ]);

            return null;
        }
    }
}