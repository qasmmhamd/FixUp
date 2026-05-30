<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    /*
    |--------------------------------------------------------------------------
    | Get Firebase Access Token
    |--------------------------------------------------------------------------
    */

    public function getAccessToken()
    {

        /*
        |--------------------------------------------------------------------------
        | Build Firebase JSON Path
        |--------------------------------------------------------------------------
        |
        | Use DIRECTORY_SEPARATOR for full compatibility
        | with Windows and Linux servers.
        |
        */

        $jsonPath = storage_path(
            'app'
            . DIRECTORY_SEPARATOR .
            'firebase'
            . DIRECTORY_SEPARATOR .
            'firebase-service-account.json'
        );

        /*
        |--------------------------------------------------------------------------
        | Check File Existence
        |--------------------------------------------------------------------------
        */

        if (!file_exists($jsonPath)) {

            \Illuminate\Support\Facades\Log::error(
                "FCM FILE MISSING AT PATH: " . $jsonPath
            );

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Read & Decode JSON File
        |--------------------------------------------------------------------------
        */

        $jsonContent = file_get_contents($jsonPath);

        $jsonKey = json_decode(
            $jsonContent,
            true
        );

        /*
        |--------------------------------------------------------------------------
        | Validate JSON Structure
        |--------------------------------------------------------------------------
        */

        if (
            !$jsonKey ||
            !isset($jsonKey['client_email']) ||
            !isset($jsonKey['private_key'])
        ) {

            \Illuminate\Support\Facades\Log::error(
                "FCM JSON INVALID STRUCTURE: ملف الـ JSON لا يحتوي على المفاتيح المطلوبة"
            );

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Generate JWT Payload
        |--------------------------------------------------------------------------
        */

        $now = time();

        $payload = [
            "iss"   => $jsonKey['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud"   => "https://oauth2.googleapis.com/token",
            "exp"   => $now + 3600,
            "iat"   => $now,
        ];

        /*
        |--------------------------------------------------------------------------
        | Generate JWT Token
        |--------------------------------------------------------------------------
        */

        $jwt = $this->generateJWT(
            $payload,
            $jsonKey['private_key']
        );

        /*
        |--------------------------------------------------------------------------
        | Request OAuth Access Token
        |--------------------------------------------------------------------------
        */

        $response = Http::asForm()->post(
            'https://oauth2.googleapis.com/token',
            [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Return Access Token
        |--------------------------------------------------------------------------
        */

        return $response['access_token'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate JWT Token
    |--------------------------------------------------------------------------
    */

    private function generateJWT(
        $payload,
        $privateKey
    ) {

        /*
        |--------------------------------------------------------------------------
        | JWT Header
        |--------------------------------------------------------------------------
        */

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        /*
        |--------------------------------------------------------------------------
        | Encode Header & Payload
        |--------------------------------------------------------------------------
        */

        $base64UrlHeader = rtrim(
            strtr(
                base64_encode(
                    json_encode($header)
                ),
                '+/',
                '-_'
            ),
            '='
        );

        $base64UrlPayload = rtrim(
            strtr(
                base64_encode(
                    json_encode($payload)
                ),
                '+/',
                '-_'
            ),
            '='
        );

        /*
        |--------------------------------------------------------------------------
        | Create Signing Data
        |--------------------------------------------------------------------------
        */

        $data = $base64UrlHeader
            . "."
            . $base64UrlPayload;

        /*
        |--------------------------------------------------------------------------
        | Sign JWT
        |--------------------------------------------------------------------------
        */

        openssl_sign(
            $data,
            $signature,
            $privateKey,
            'SHA256'
        );

        /*
        |--------------------------------------------------------------------------
        | Encode Signature
        |--------------------------------------------------------------------------
        */

        $base64UrlSignature = rtrim(
            strtr(
                base64_encode($signature),
                '+/',
                '-_'
            ),
            '='
        );

        /*
        |--------------------------------------------------------------------------
        | Return Final JWT
        |--------------------------------------------------------------------------
        */

        return $data
            . "."
            . $base64UrlSignature;
    }
}