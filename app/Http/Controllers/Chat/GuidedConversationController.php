<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\GuidedConversationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @class GuidedConversationController
 *
 * Handles structured (guided) chat conversations between customers and workers.
 *
 * This controller manages:
 * - Creating conversations based on topics
 * - Sending predefined message templates
 * - Retrieving conversation messages
 *
 * It acts as a thin layer over GuidedConversationService.
 */
class GuidedConversationController extends Controller
{
    /**
     * Guided Conversation Service instance
     *
     * @var GuidedConversationService
     */
    protected $guidedConversationService;

    /**
     * Inject service dependency
     *
     * @param GuidedConversationService $guidedConversationService
     */
    public function __construct(GuidedConversationService $guidedConversationService)
    {
        $this->guidedConversationService = $guidedConversationService;
    }

    /**
     * Create a new guided conversation between customer and worker.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Validate input
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'topic_id'  => 'required|exists:message_topics,id',
        ]);

        // Create conversation
        $conversation = $this->guidedConversationService->createConversation(
            Auth::id(),
            $request->worker_id,
            $request->topic_id
        );

        return response()->json([
            'success'      => true,
            'conversation' => $conversation
        ]);
    }

    /**
     * Send a predefined message template inside a conversation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        // Validate input
        $request->validate([
            'conversation_id' => 'required|integer',
            'template_id'     => 'required|integer',
        ]);

        // Send template message
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

    /**
     * Retrieve messages for a conversation (topic-based templates/messages).
     *
     * @param int $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function templates($conversationId)
    {
        $messages = $this->guidedConversationService
            ->getTemplatesForConversation(
                $conversationId,
                Auth::id()
            );

        return response()->json([
            'success'  => true,
            'messages' => $messages
        ]);
    }
}