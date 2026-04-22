<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
             
          'about' => 'nullable|string',
          'years_experience' => 'nullable|integer|min:0',

          'services' => 'nullable|array',
          'services.*' => 'exists:services,id',

          'images' => 'nullable|array',
          'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',

          'delete_images' => 'nullable|array',
          'delete_images.*' => 'exists:images,id',
    
        ];
    }
}
