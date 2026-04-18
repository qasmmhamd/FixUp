<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
        'id' => $this->id,

        'about' => $this->about,
        'status' => $this->status,
        'years_experience' => $this->years_experience,

        // بيانات المستخدم
        'user' => [
            'id' => $this->user?->id,
            'name' => $this->user?->name,
            'profile_image' => $this->user?->profile_image_url,
        ],

        // المهنة
        'career' => [
            'id' => $this->career?->id,
            'name' => $this->career?->name,
        ],

        'created_at' => $this->created_at?->format('Y-m-d H:i'),
    ];
    }
}
