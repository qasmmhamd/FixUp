<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rules;
class RegisterUserRequest extends FormRequest
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
            // user
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number'=> ['required', 'string', 'max:20'],
            'birth_date'=> ['required', 'date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // address
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'detailed_address' => ['required', 'string', 'max:255'],
            'area_address_id' => ['nullable', 'exists:area_addresses,id'],
        ];
    }
}
