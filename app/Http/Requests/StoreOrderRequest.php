<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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

        'description' => ['required', 'string', 'max:1000'],

        'scheduled_at' => ['nullable', 'date', 'after:now'],



        
        'services' => ['required', 'array', 'min:1'],
        'services.*' => ['exists:services,id'],

        'address_id' => ['nullable', 'exists:addresses,id'],

        'address' => ['nullable', 'array'],

        'images' => ['nullable', 'array'],
        'images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],

        'address.latitude' => ['required_with:address', 'numeric'],
        'address.longitude' => ['required_with:address', 'numeric'],
        'address.detailed_address' => ['required_with:address', 'string'],
        'address.area_address_id' => ['nullable', 'exists:area_addresses,id'],
    ];
    }
}
