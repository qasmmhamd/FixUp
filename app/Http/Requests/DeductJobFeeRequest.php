<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DeductJobFeeRequest extends FormRequest
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
            'job_id' => ['required', 'integer'],
            'career_id' => ['required', 'exists:careers,id'],
            'idempotency_key' => ['required', 'string', 'unique:wallet_transactions,idempotency_key']
        ];
    }

}
