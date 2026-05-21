<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\MessageTemplate;
use App\Models\PriceOffer;
use App\Models\Worker as WorkerModel;
use App\Http\Requests\StoreMessageTemplateRequest;
use App\Models\MessageTopic;
use App\Http\Requests\StoreMessageTopicRequest;

class GuidedConversationService
{
    // إنشاء المحادثة
    public function createConversation($customerId, $workerId, $topic_id)
    {
         $conversation = Conversation::create([
        'customer_id' => $customerId,
        'worker_id'   => $workerId,
        'topic_id'    => $topic_id,
        'status'      => 'open',
    ]);

    /*
    |---------------------------------------------------------------
    | ربط المحادثة مع عرض السعر
    |---------------------------------------------------------------
    | نبحث عن آخر PriceOffer بين نفس العامل والطلب المفتوح لهذا العميل
    | ثم نحدّث conversation_id فيه.
    */
    $priceOffer =PriceOffer::where('worker_id', $workerId)
        ->whereHas('order', function ($query) use ($customerId) {
              $query->where('user_id', $customerId);
        })
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

    // إرسال رسالة
    public function sendTemplate($conversationId, $templateId, $userId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // منع غير المشاركين
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

        // فقط رسائل نفس الموضوع
        if ($template->topic_id !== $conversation->topic_id) {
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
  $worker = WorkerModel::where('user_id', $userId)->first();

$isCustomer = $conversation->customer_id == $userId;

$isWorker = $worker && $conversation->worker_id == $worker->id;

if (!$isCustomer && !$isWorker) {
    abort(403, 'Unauthorized');
}
    return ChatMessage::where(
        'conversation_id',
        $conversationId
    )
    ->orderBy('created_at', 'asc')
    ->get();
    }

    public function storeMessageTemplate(
        StoreMessageTemplateRequest $request
    ) {
        $template = MessageTemplate::create(
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Message template created successfully',
            'data' => $template
        ], 201);
    }
    public function getTemplates(
    ?string $topic = null,
    ?string $senderType = null
) {
    return MessageTemplate::query()

        ->when($topic, function ($q) use ($topic) {

            $q->where(
                'topic_id',
                $topic
            );
        })

        ->when($senderType, function ($q) use ($senderType) {

            $q->where(
                'sender_type',
                $senderType
            );
        })

        ->latest()
        ->get();
}
  public function storeTopic(
        StoreMessageTopicRequest $request
    ) {
        return MessageTopic::create([
            'topic' => $request->topic
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | عرض جميع المواضيع
    |--------------------------------------------------------------------------
    */

    public function topics()
    {
        return MessageTopic::latest()->get();
    }
    public function deleteTopic(int $id)
{
    $topic = MessageTopic::findOrFail($id);

    $topic->delete();

    return true;
}
public function updateTopic(
    int $id,
    array $data
) {
    $topic = MessageTopic::findOrFail($id);

    $topic->update([
        'topic' => $data['topic']
    ]);

    return $topic;
}
}