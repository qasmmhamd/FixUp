<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Http\Requests\StoreMessageTemplateRequest;
use App\Http\Requests\StoreMessageTopicRequest;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\MessageTemplate;
use App\Models\MessageTopic;
use App\Models\PriceOffer;
use App\Services\NotificationService;
use App\Models\Worker as WorkerModel;

/**
 * Class GuidedConversationService
 *
 * Handles guided chat conversations between customers and workers.
 *
 * Responsibilities:
 * - Creating conversations between users
 * - Linking conversations with price offers
 * - Sending template-based messages
 * - Managing chat messages
 * - Managing message templates and topics
 *
 * This service acts as the business logic layer between controllers
 * and database models for the chat system.
 */
class GuidedConversationService
{
    /**
     * Create a new conversation between customer and worker.
     *
     * Also attempts to link the conversation with the latest
     * related price offer (if exists).
     *
     * @param int $customerId
     * @param int $workerId
     * @param int $topic_id
     * @return Conversation
     */
    public function createConversation(
        $customerId,
        $workerId,
        $topic_id
    ) {
        $conversation = Conversation::create([
            'customer_id' => $customerId,
            'worker_id'   => $workerId,
            'topic_id'    => $topic_id,
            'status'      => 'open',
        ]);

        $priceOffer = PriceOffer::where('worker_id', $workerId)
            ->whereHas('order', fn ($query) =>
                $query->where('user_id', $customerId)
            )
            ->whereNull('conversation_id')
            ->latest()
            ->first();

        if ($priceOffer) {
            $priceOffer->update([
                'conversation_id' => $conversation->id,
            ]);
        }

        return $conversation;
    }

    /**
     * Send a predefined template message inside a conversation.
     *
     * Enforces:
     * - Authorization (customer or worker only)
     * - Conversation status validation
     * - Topic-template consistency
     *
     * @param int $conversationId
     * @param int $templateId
     * @param int $userId
     * @return ChatMessage
     *
     * @throws \Exception
     */
    public function sendTemplate(
        $conversationId,
        $templateId,
        $userId
    ) {
        $conversation = Conversation::findOrFail($conversationId);

        if (
            $conversation->customer_id != $userId &&
            $conversation->worker->user_id != $userId
        ) {
            throw new \Exception('Unauthorized');
        }

        if ($conversation->status === 'closed') {
            throw new \Exception('Conversation closed');
        }

        $template = MessageTemplate::findOrFail($templateId);

        if ($template->topic_id !== $conversation->topic_id) {
            throw new \Exception('Invalid template');
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $userId,
            'message'         => $template->text,
        ]);
                
             $receiver = $conversation->customer_id == $userId
            ? $conversation->worker->user
            : $conversation->customer;

        app(NotificationService::class)->send(
            $receiver,
            'رسالة جديدة',
            $template->text,
            'chat_message',
            [
                'conversation_id' => $conversation->id,
                'message_id'      => $message->id,
                'url'             => '/chat/' . $conversation->id,
            ]
        );

        if ($conversation->status === 'open') {
            $conversation->update(['status' => 'active']);
        }

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    /**
     * Get all messages inside a conversation
     * after validating access permissions.
     *
     * @param int $conversationId
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getTemplatesForConversation(
        $conversationId,
        $userId
    ) {
        $conversation = Conversation::findOrFail($conversationId);

        $worker = WorkerModel::where('user_id', $userId)->first();

        $isCustomer = $conversation->customer_id == $userId;
        $isWorker   = $worker && $conversation->worker_id == $worker->id;

        if (!$isCustomer && !$isWorker) {
            abort(403, 'Unauthorized');
        }

        return ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Create a new message template.
     *
     * @param StoreMessageTemplateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMessageTemplate(
        StoreMessageTemplateRequest $request
    ) {
        $template = MessageTemplate::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Message template created successfully',
            'data'    => $template
        ], 201);
    }

    /**
     * Retrieve message templates with optional filters.
     *
     * @param string|null $topic
     * @param string|null $senderType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTemplates(
        ?int $topic = null,
        ?string $senderType = null
    ) {
        return MessageTemplate::query()

            ->when($topic, function ($q) use ($topic) {
                $q->where('topic_id', $topic);
            })

            ->when($senderType, function ($q) use ($senderType) {
                $q->where('sender_type', $senderType);
            })

            ->latest()
            ->get();
    }

    /**
     * Delete a message template.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTemplate(int $id)
    {
        return MessageTemplate::findOrFail($id)->delete();
    }

    /**
     * Create a new message topic.
     *
     * @param StoreMessageTopicRequest $request
     * @return MessageTopic
     */
    public function storeTopic(StoreMessageTopicRequest $request)
    {
        return MessageTopic::create([
            'topic' => $request->topic
        ]);
    }

    /**
     * Get all message topics.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function topics()
    {
        return MessageTopic::latest()->get();
    }

    /**
     * Delete a message topic.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTopic(int $id)
    {
        return MessageTopic::findOrFail($id)->delete();
    }

    /**
     * Update an existing message topic.
     *
     * @param int $id
     * @param array $data
     * @return MessageTopic
     */
    public function updateTopic(int $id, array $data)
    {
        $topic = MessageTopic::findOrFail($id);

        $topic->update([
            'topic' => $data['topic']
        ]);

        return $topic;
    }
}