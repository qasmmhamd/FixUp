<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobFeeRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
     public function rules()
    {
        return [
            'career_id' => ['required', 'exists:careers,id'],
            'fee' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean']
        ];
    }
}
