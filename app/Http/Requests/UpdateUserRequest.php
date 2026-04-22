<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @class UpdateUserRequest
 * 
 * Handles validation for updating user profiles in the FixUp system.
 * This request validates user information including personal details,
 * profile picture, and address information.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool True if the user is authorized to update their profile
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
            'name' => ['sometimes', 'string', 'max:255'],
            'phone_number' => ['sometimes', 'string', 'max:20'],

            // image
            'profile_picture' => ['sometimes', 'image', 'mimes:jpg,jpeg,png'],

            // address
            'latitude' => ['sometimes', 'numeric'],
            'longitude' => ['sometimes', 'numeric'],
            'detailed_address' => ['sometimes', 'string', 'max:255'],
            'area_address_id' => ['nullable', 'exists:area_addresses,id'],

        ];
    }
}
