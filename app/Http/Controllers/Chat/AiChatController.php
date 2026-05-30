<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @class AiChatController
 *
 * Handles AI-powered chat interactions between the user and the system.
 * Acts as a thin layer between HTTP requests and AiChatService logic.
 *
 * Responsibilities:
 * - Accept user messages
 * - Forward requests to AI service
 * - Return AI-generated responses
 * - Retrieve chat history
 */
class AiChatController extends Controller
{
    /**
     * AI Chat Service instance
     *
     * @var AiChatService
     */
    protected AiChatService $aiChatService;

    /**
     * Inject dependencies
     *
     * @param AiChatService $aiChatService
     */
    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
    }

    /**
     * Send a message to the AI and receive a response.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ask(Request $request)
    {
        // Validate input
        $request->validate([
            'message' => 'required|string'
        ]);

        // Get AI response
        $reply = $this->aiChatService->askAi(
            Auth::id(),
            $request->message
        );

        return response()->json([
            'success' => true,
            'reply'   => $reply
        ]);
    }

    /**
     * Retrieve all chat messages for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function messages()
    {
        return response()->json([
            'success'  => true,
            'messages' => $this->aiChatService->getMessages(Auth::id())
        ]);
    }
}