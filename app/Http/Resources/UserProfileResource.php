<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @class UserProfileResource
 * 
 * Transforms user model data for API responses.
 * This resource formats user profile information including personal details,
 * contact information, profile images, and address data for JSON output.
 */
class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The incoming HTTP request
     * @return array<string, mixed> The transformed user profile data
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,

            'phone_number' => $this->phone_number,

            'profile_image' => $this->profile_image
                ? asset('storage/'.$this->profile_image)
                : null,

            'address' => [
                'latitude' => $this->address?->latitude,
                'longitude' => $this->address?->longitude,
                'detailed_address' => $this->address?->detailed_address,

                'area_address' => [
                    'id' => $this->address?->areaAddress?->id,
                    'area_name' => $this->address?->areaAddress?->area_name,
                ],
            ],

        ];
    }
}
