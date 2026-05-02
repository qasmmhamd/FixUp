<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @class WorkerResource
 * 
 * Transforms worker model data for API responses.
 * This resource formats worker information including profile details,
 * user information, career data, and related entities for JSON output.
 */
class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The incoming HTTP request
     * @return array<string, mixed> The transformed worker data
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

        'about' => $this->about,
        'status' => $this->status,
        'years_experience' => $this->years_experience,

        // user + address
        'user' => [
            'id' => $this->user?->id,
            'name' => $this->user?->name,
            'profile_image' => $this->user?->profile_image_url,

             'address' => [
                'latitude' => $this->address?->latitude,
                'longitude' => $this->address?->longitude,
                'detailed_address' => $this->address?->detailed_address,

                'area_address' => [
                    'id' => $this->address?->areaAddress?->id,
                    'area_name' => $this->address?->areaAddress?->area_name,
                ],
            ],
           

        ],

        // career
        'career' => [
            'id' => $this->career?->id,
            'name' => $this->career?->name,
        ],

        // ✅ أضف هذا (كان ناقص)
        'services' => $this->services->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
            ];
        }),

        // images
        'images' => $this->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => asset('storage/' . $image->path),
            ];
        }),

        'created_at' => $this->created_at?->format('Y-m-d H:i'),
    ];
    }
}
