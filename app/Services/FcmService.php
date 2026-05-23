<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function send($fcmToken, $title, $body, $data = [])
    {
        try {
            if (empty($fcmToken)) {
                Log::warning('FCM SKIPPED: empty token');
                return null;
            }

            $accessToken = (new FirebaseService())->getAccessToken();
            if (!$accessToken) {
                Log::error('FCM ERROR: missing access token');
                return null;
            }

            // 1. تجهيز مصفوفة data وتحويل كل القيم إلى سلاسل نصية (Strings) إجبارياً
            $formattedData = [];
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $formattedData[$key] = (string) $value;
                }
            }

            // 2. حقن البيانات الأساسية داخل الـ data لتقرأها الخلفية بالتوازي
            $formattedData['title'] = (string) $title;
            $formattedData['body'] = (string) $body;
            $formattedData['url'] = isset($data['url']) ? (string)$data['url'] : '/customer/orders';

            // 3. بناء الـ Payload الرسمي لـ Firebase HTTP v1
            $payload = [
                "message" => [
                    "token" => $fcmToken,
                    // الكائن العلوي للأمامية (الـ Hook) وأنظمة التشغيل الافتراضية
                    "notification" => [
                        "title" => (string) $title,
                        "body" => (string) $body,
                    ],
                    // الكائن المخصص للخلفية (Service Worker) والروابط
                    "data" => $formattedData
                ]
            ];

            $response = Http::timeout(10)
                ->withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send", $payload);

            Log::info('FCM RESPONSE', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

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