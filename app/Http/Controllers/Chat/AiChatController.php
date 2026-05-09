<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiChatController extends Controller
{
    protected $aiChatService;

    public function __construct(
        AiChatService $aiChatService
    ) {
        $this->aiChatService = $aiChatService;
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $reply = $this->aiChatService->askAi(
            Auth::id(),
            $request->message
        );

        return response()->json([
            'success' => true,
            'reply' => $reply
        ]);
    }

    public function messages()
    {
        return response()->json([
            'success' => true,
            'messages' => $this->aiChatService
                ->getMessages(Auth::id())
        ]);
    }
}