<?php

namespace App\Services;

use App\Models\AiChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

/**
 * Class AiChatService
 *
 * Handles AI-powered chat interactions using OpenAI API.
 *
 * Responsibilities:
 * - Stores user messages and AI responses
 * - Maintains chat history context
 * - Communicates with OpenAI Chat Completions API
 *
 * Business Rules:
 * - Only last 20 messages are sent as context
 * - System prompt restricts AI to FixUp domain
 * - Every user message and AI reply is persisted
 *
 * External Dependencies:
 * - OpenAI API (gpt-4o-mini model)
 *
 * @package App\Services
 */
class AiChatService
{
    /**
     * Send a message to AI and return generated response.
     *
     * This method:
     * - Stores user message
     * - Builds conversation history
     * - Sends request to OpenAI
     * - Stores AI response
     *
     * @param int $userId
     * @param string $message
     *
     * @return string AI-generated response or error message
     *
     * @throws \Throwable
     */
    public function askAi(
        $userId,
        $message
    ) {
        /*
        |--------------------------------------------------------------------------
        | Store User Message
        |--------------------------------------------------------------------------
        */

        AiChatMessage::create([
            'user_id' => $userId,
            'role'    => 'user',
            'message' => $message
        ]);

        /*
        |--------------------------------------------------------------------------
        | Retrieve Conversation History
        |--------------------------------------------------------------------------
        */

        $history = AiChatMessage::where('user_id', $userId)
            ->latest()
            ->take(20)
            ->get()
            ->reverse();

        /*
        |--------------------------------------------------------------------------
        | Build OpenAI Message Payload
        |--------------------------------------------------------------------------
        */
        $systemPrompt = File::get(
          resource_path('prompts/fixup-assistant.txt')
            );

        $messages = [
            [
                'role'    => 'system',
                'content' => $systemPrompt

            ]
        ];

        foreach ($history as $chat) {
            $messages[] = [
                'role'    => $chat->role,
                'content' => $chat->message
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | OpenAI API Request
        |--------------------------------------------------------------------------
        */

        $response = Http::withOptions([
                'verify'  => 'C:\php83\extras\ssl\cacert.pem',
                'timeout' => 60,
            ])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'    => 'gpt-4o-mini',
                'messages' => $messages,
            ]);

        /*
        |--------------------------------------------------------------------------
        | Handle API Failure
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {

            Log::error('OpenAI API Error', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'headers' => $response->headers(),
            ]);

            return $response->body();
        }

        /*
        |--------------------------------------------------------------------------
        | Parse Response Safely
        |--------------------------------------------------------------------------
        */

        $data = $response->json();

        $reply = $data['choices'][0]['message']['content'] ?? null;

        if (!$reply) {

            Log::error('OpenAI Invalid Response Structure', $data);

            return "لم يتم استلام رد من الذكاء الاصطناعي.";
        }

        /*
        |--------------------------------------------------------------------------
        | Store AI Response
        |--------------------------------------------------------------------------
        */

        AiChatMessage::create([
            'user_id' => $userId,
            'role'    => 'assistant',
            'message' => $reply
        ]);

        return $reply;
    }

    /**
     * Retrieve full chat history for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMessages($userId)
    {
        return AiChatMessage::where('user_id', $userId)
            ->orderBy('created_at')
            ->get();
    }
}