<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    public function getAccessToken()
    {
        $jsonKey = json_decode(file_get_contents(storage_path('app/firebase/firebase.json')), true);

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

        return $response['access_token'];
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