<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    // public function getAccessToken()
    // {
    //     $jsonKey = json_decode(file_get_contents(storage_path('app/firebase/firebase-service-account.json')), true);

    //     $now = time();

    //     $payload = [
    //         "iss" => $jsonKey['client_email'],
    //         "scope" => "https://www.googleapis.com/auth/firebase.messaging",
    //         "aud" => "https://oauth2.googleapis.com/token",
    //         "exp" => $now + 3600,
    //         "iat" => $now,
    //     ];

    //     $jwt = $this->generateJWT($payload, $jsonKey['private_key']);

    //     $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
    //         'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    //         'assertion' => $jwt,
    //     ]);

    //     return $response['access_token'];
    // }


    public function getAccessToken()
    {
        // 🛠️ استخدام DIRECTORY_SEPARATOR لضمان التوافق التام مع الويندوز والسيرفرات
        $jsonPath = storage_path('app' . DIRECTORY_SEPARATOR . 'firebase' . DIRECTORY_SEPARATOR . 'firebase-service-account.json');

        // 🛡️ فحص أمان: إذا كان الملف غير موجود، سجل خطأ واضحاً بالمسار في الـ Log بدل أن ينهار الكود
        if (!file_exists($jsonPath)) {
            \Illuminate\Support\Facades\Log::error("FCM FILE MISSING AT PATH: " . $jsonPath);
            return null;
        }

        $jsonContent = file_get_contents($jsonPath);
        $jsonKey = json_decode($jsonContent, true);

        // التأكد من أن الـ JSON تم فكه بنجاح ويحتوي على البيانات المطلوبة
        if (!$jsonKey || !isset($jsonKey['client_email']) || !isset($jsonKey['private_key'])) {
            \Illuminate\Support\Facades\Log::error("FCM JSON INVALID STRUCTURE: ملف الـ JSON لا يحتوي على المفاتيح المطلوبة");
            return null;
        }

        $now = time();

        $payload = [
            "iss" => $jsonKey['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => "https://oauth2.googleapis.com/token",
            "exp" => $now + 3600,
            "iat" => $now,
        ];

        $jwt = $this->generateJWT($payload, $jsonKey['private_key']);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        // حماية الإرجاع في حال فشل طلب الـ Token
        return $response['access_token'] ?? null;
    }
    private function generateJWT($payload, $privateKey)
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];

        $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $data = $base64UrlHeader . "." . $base64UrlPayload;

        openssl_sign($data, $signature, $privateKey, 'SHA256');

        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $data . "." . $base64UrlSignature;
    }
}       