<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\GuidedConversationService;
use App\Http\Requests\UpdateMessageTopicRequest;
use App\Http\Requests\StoreMessageTemplateRequest;
use App\Http\Requests\StoreMessageTopicRequest;

class MessageTemplateController extends Controller
{
    public function __construct(
        private GuidedConversationService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | عرض الرسائل الجاهزة
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        return response()->json([

            'status' => true,

            'data' => $this->service->getTemplates(

                $request->topic_id,

                $request->sender_type
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | إنشاء رسالة جاهزة
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreMessageTemplateRequest $request
    ) {
        return response()->json([

            'status' => true,

            'message' => 'Message template created successfully',

            'data' => $this->service
                ->storeMessageTemplate($request)

        ], 201);
    }
    public function destroyTemplate(int $id)
{
    $this->service->deleteTemplate($id);

    return response()->json([
        'status' => true,
        'message' => 'Template deleted successfully'
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | عرض المواضيع
    |--------------------------------------------------------------------------
    */

    public function topics()
    {
        return response()->json([

            'status' => true,

            'data' => $this->service
                ->topics()

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | إنشاء موضوع
    |--------------------------------------------------------------------------
    */

    public function storeTopic(
        StoreMessageTopicRequest $request
    ) {
        return response()->json([

            'status' => true,

            'message' => 'Topic created successfully',

            'data' => $this->service
                ->storeTopic($request)

        ], 201);
    }
    public function updateTopic(
    UpdateMessageTopicRequest $request,
    int $id
) {
    return response()->json([
        'status' => true,
        'message' => 'Topic updated successfully',
        'data' => $this->service->updateTopic(
            $id,
            $request->validated()
        )
    ]);
}
public function destroyTopic(int $id)
{
    $this->service->deleteTopic($id);

    return response()->json([
        'status' => true,
        'message' => 'Topic deleted successfully'
    ]);
}
}