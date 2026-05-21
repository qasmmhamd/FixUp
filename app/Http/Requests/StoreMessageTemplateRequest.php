<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => [
                'required',
                'string',
                'max:1000'
            ],

            'sender_type' => [
                'required',
                'in:customer,worker'
            ],

            'topic_id' => [
                'required',
                'exists:message_topics,id'
            ],
        ];
    }
}