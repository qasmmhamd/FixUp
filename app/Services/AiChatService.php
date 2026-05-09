<?php

namespace App\Services;

use App\Models\AiChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    public function askAi($userId, $message)
    {
        // حفظ رسالة المستخدم
        AiChatMessage::create([
            'user_id' => $userId,
            'role' => 'user',
            'message' => $message
        ]);

        // جلب آخر الرسائل
        $history = AiChatMessage::where('user_id', $userId)
            ->latest()
            ->take(20)
            ->get()
            ->reverse();

        $messages = [
            [
                'role' => 'system',
                'content' => 'أنت مساعد ذكي داخل منصة FixUp لخدمات المنزل فقط.'
            ]
        ];

        foreach ($history as $chat) {
            $messages[] = [
                'role' => $chat->role,
                'content' => $chat->message
            ];
        }

        // طلب OpenAI
        $response = Http::withOptions([
            'verify' => 'C:\php83\extras\ssl\cacert.pem',
            'timeout' => 60,
        ])->withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => $messages,
        ]);

        // 🔴 لو فيه خطأ من API
       if (!$response->successful()) {
    Log::error('OpenAI Error', [
        'status' => $response->status(),
        'body' => $response->body(),
        'headers' => $response->headers(),
    ]);

    return $response->body(); // 👈 مهم جدًا للتشخيص
}

        $data = $response->json();

        // 🔴 حماية من missing choices
        $reply = $data['choices'][0]['message']['content'] ?? null;

        if (!$reply) {
            Log::error('OpenAI Invalid Response', $data);
            return "لم يتم استلام رد من الذكاء الاصطناعي.";
        }

        // حفظ رد AI
        AiChatMessage::create([
            'user_id' => $userId,
            'role' => 'assistant',
            'message' => $reply
        ]);

        return $reply;
    }

    public function getMessages($userId)
    {
        return AiChatMessage::where('user_id', $userId)
            ->orderBy('created_at')
            ->get();
    }
}