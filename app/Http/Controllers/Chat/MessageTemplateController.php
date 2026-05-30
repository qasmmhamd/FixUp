<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GuidedConversationService;
use App\Http\Requests\UpdateMessageTopicRequest;
use App\Http\Requests\StoreMessageTemplateRequest;
use App\Http\Requests\StoreMessageTopicRequest;

/**
 * @class MessageTemplateController
 *
 * Manages predefined chat message templates and conversation topics.
 *
 * This controller is responsible for:
 * - Retrieving message templates with filters
 * - Creating and deleting message templates
 * - Managing message topics (CRUD operations)
 *
 * It delegates all business logic to GuidedConversationService.
 */
class MessageTemplateController extends Controller
{
    /**
     * Guided Conversation Service instance
     *
     * @var GuidedConversationService
     */
    public function __construct(
        private GuidedConversationService $service
    ) {}

    /**
     * Get all message templates with optional filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplates(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $this->service->getTemplates(
                $request->topic,
                $request->sender_type
            )
        ]);
    }

    /**
     * Create a new message template.
     *
     * @param StoreMessageTemplateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMessageTemplateRequest $request)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Message template created successfully',
            'data'    => $this->service->storeMessageTemplate($request)
        ], 201);
    }

    /**
     * Delete a message template.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTemplate(int $id)
    {
        $this->service->deleteTemplate($id);

        return response()->json([
            'status'  => true,
            'message' => 'Template deleted successfully'
        ]);
    }

    /**
     * Get all message topics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topics()
    {
        return response()->json([
            'status' => true,
            'data'   => $this->service->topics()
        ]);
    }

    /**
     * Create a new message topic.
     *
     * @param StoreMessageTopicRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTopic(StoreMessageTopicRequest $request)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Topic created successfully',
            'data'    => $this->service->storeTopic($request)
        ], 201);
    }

    /**
     * Update an existing message topic.
     *
     * @param UpdateMessageTopicRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTopic(UpdateMessageTopicRequest $request, int $id)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Topic updated successfully',
            'data'    => $this->service->updateTopic(
                $id,
                $request->validated()
            )
        ]);
    }

    /**
     * Delete a message topic.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTopic(int $id)
    {
        $this->service->deleteTopic($id);

        return response()->json([
            'status'  => true,
            'message' => 'Topic deleted successfully'
        ]);
    }
}