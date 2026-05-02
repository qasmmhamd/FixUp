<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Http;
class NotificationPriceOfferController extends Controller
{
     public function index()
    {      
        return Auth::user()->notifications;
    }

    public function unread()
    {
        return Auth::user()->unreadNotifications;
        
    }
    public function send()
{
    $firebase = new FirebaseService();

    $token = $firebase->getAccessToken();

    $fcmToken = "USER_DEVICE_TOKEN";

    $response = Http::withToken($token)->post(
        'https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send',
        [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => "عرض سعر جديد",
                    "body" => "تم إرسال عرض سعر لك"
                ],
                "data" => [
                    "type" => "price_offer",
                    "id" => "123"
                ]
            ]
        ]
    );

    return $response->json();
}
public function testToken()
    {
        $firebase = new FirebaseService();
        return $firebase->getAccessToken();
    }

public function sendNotification()
{
    $accessToken = (new FirebaseService())->getAccessToken();

    $fcmToken = "cFYMKMB0AWHeVxDyieDDlF:APA91bG3ZTSZrfofw_iIXcaegnzQWiCK00tshMfSeNfJ6XXp8GubVJR9c-Ph8ui4ZxLQrnU-fUdI__BWgY4TrIpqsH_9W0cJ6DQd3Bbkj6U1wTz5vlvEHIM";

    $response = Http::withToken($accessToken)
        ->post("https://fcm.googleapis.com/v1/projects/fixup-c687c/messages:send", [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => "🚀 Test Notification",
                    "body" => "This is a test from Laravel"
                ],
                "data" => [
                    "type" => "test",
                    "id" => "123"
                ]
            ]
        ]);

    return $response->json();
}
}
