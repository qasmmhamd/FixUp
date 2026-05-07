<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\MessageTemplate;

class GuidedConversationService
{
    // إنشاء المحادثة
    public function createConversation($customerId, $workerId, $topic)
    {
        return Conversation::create([
            'customer_id' => $customerId,
            'worker_id' => $workerId,
            'topic' => $topic,
            'status' => 'open',
        ]);
    }

    // إرسال رسالة
    public function sendTemplate($conversationId, $templateId, $userId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // منع غير المشاركين
        if (
            $conversation->customer_id != $userId &&
            $conversation->worker_id != $userId
        ) {
            throw new \Exception('Unauthorized');
        }

        if ($conversation->status === 'closed') {
            throw new \Exception('Conversation closed');
        }

        $template = MessageTemplate::findOrFail($templateId);

        // فقط رسائل نفس الموضوع
        if ($template->topic!== $conversation->topic) {
            throw new \Exception('Invalid template');
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $template->text,
        ]);

        if ($conversation->status === 'open') {
            $conversation->update([
                'status' => 'active'
            ]);
        }

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    // جلب الرسائل حسب الموضوع
    public function getTemplatesForConversation($conversationId, $userId)
{
    $conversation = Conversation::findOrFail($conversationId);

    // حماية المحادثة
    if (
        $conversation->customer_id != $userId &&
        $conversation->worker_id != $userId
    ) {
        abort(403, 'Unauthorized');
    }

    // جلب جميع رسائل المحادثة
    return ChatMessage::where(
        'conversation_id',
        $conversationId
    )
    ->orderBy('created_at', 'asc')
    ->get();
    }
}