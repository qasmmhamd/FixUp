<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $topicId = $this->route('id');

        return [
            'topic' => [
                'required',
                'string',
                'max:255',
                'unique:message_topics,topic,' . $topicId
            ]
        ];
    }
}