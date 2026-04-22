<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @class StoreWorkerRequest
 * 
 * Handles validation for creating new worker profiles in the FixUp system.
 * This request validates worker information including career, experience,
 * services, and uploaded images.
 */
class StoreWorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool True if the user is authorized to create workers
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string> Validation rules
     */
    public function rules(): array
    {
        return [
            'career_id' => 'required|exists:careers,id',
            'about' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
            'status' => 'in:active,blocked,waiting',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
