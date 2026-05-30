<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/**
 * Class NotificationPriceOfferController
 *
 * Handles notification operations related to price offers and Firebase testing.
 * Includes:
 * - Fetching user notifications
 * - Fetching unread notifications
 * - Testing FCM token generation
 * - Sending test push notifications
 */
class NotificationPriceOfferController extends Controller
{
    /**
     * Get all notifications for authenticated user.
     *
     * @return mixed
     */
    public function index()
    {
        return Auth::user()->notifications;
    }

    /**
     * Get unread notifications for authenticated user.
     *
     * @return mixed
     */
    public function unread()
    {
        return Auth::user()->unreadNotifications;
    }

    /**
     * Send test notification via Firebase Cloud Messaging (FCM).
     *
     * @return mixed
     */
    public function send()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Firebase Access Token
        |--------------------------------------------------------------------------
        */

        $firebase = new FirebaseService();
        $token = $firebase->getAccessToken();

        /*
        |--------------------------------------------------------------------------
        | Target Device Token (TEST ONLY)
        |--------------------------------------------------------------------------
        */

        $fcmToken = "USER_DEVICE_TOKEN";

        /*
        |--------------------------------------------------------------------------
        | Send Request to FCM API
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken($token)->post(
            'https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send',
            [
                "message" => [
                    "token" => $fcmToken,
                    "notification" => [
                        "title" => "عرض سعر جديد",
                        "body"  => "تم إرسال عرض سعر لك"
                    ],
                    "data" => [
                        "type" => "price_offer",
                        "id"   => "123"
                    ]
                ]
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return $response->json();
    }

    /**
     * Get Firebase access token (debug).
     *
     * @return mixed
     */
    public function testToken()
    {
        $firebase = new FirebaseService();
        return $firebase->getAccessToken();
    }

    /**
     * Send advanced test notification with fixed token.
     *
     * @return mixed
     */
    public function sendNotification()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Access Token
        |--------------------------------------------------------------------------
        */

        $accessToken = (new FirebaseService())->getAccessToken();

        /*
        |--------------------------------------------------------------------------
        | Target FCM Token (TEST ONLY)
        |--------------------------------------------------------------------------
        */

        $fcmToken = "cFYMKMB0AWHeVxDyieDDlF:APA91bG3ZTSz...";

        /*
        |--------------------------------------------------------------------------
        | Send Notification
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken($accessToken)
            ->post("https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send", [
                "message" => [
                    "token" => $fcmToken,
                    "notification" => [
                        "title" => "🚀 Test Notification",
                        "body"  => "This is a test from Laravel"
                    ],
                    "data" => [
                        "type" => "test",
                        "id"   => "123"
                    ]
                ]
            ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return $response->json();
    }
}