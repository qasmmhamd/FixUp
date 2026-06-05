<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkercustomerOrderResource extends JsonResource
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
            'career_id' => $this->career_id,
            'about' => $this->about,
            'status' => $this->status,
            'years_experience' => $this->years_experience,
            'created_at' => $this->created_at,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'image' => $this->user->image,
                'created_at' => $this->user->created_at,
            ],
        ];
    }
}
