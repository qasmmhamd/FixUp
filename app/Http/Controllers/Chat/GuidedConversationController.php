<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GuidedConversationService;
use Illuminate\Support\Facades\Auth;

class GuidedConversationController extends Controller
{
    protected $guidedConversationService;

    public function __construct(GuidedConversationService $guidedConversationService)
    {
        $this->guidedConversationService = $guidedConversationService;
    }

    // إنشاء المحادثة
    public function create(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'topic' => 'required|string',
        ]);

        $conversation = $this->guidedConversationService->createConversation(
            Auth::id(),
            $request->worker_id,
            $request->topic
        );

        return response()->json([
            'success' => true,
            'conversation' => $conversation
        ]);
    }

    // إرسال رسالة جاهزة
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'template_id' => 'required|integer',
        ]);

        $message = $this->guidedConversationService->sendTemplate(
            $request->conversation_id,
            $request->template_id,
            Auth::id()
        );

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    // جلب الرسائل حسب موضوع المحادثة
    public function templates($conversationId)
    {
        $messages = $this->guidedConversationService
        ->getTemplatesForConversation(
            $conversationId,
            Auth::id()
        );

    return response()->json([
        'success' => true,
        'messages' => $messages
    ]);
    }
}