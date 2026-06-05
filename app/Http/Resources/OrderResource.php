<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'scheduled_at' => $this->scheduled_at,
            'created_at' => $this->created_at,

            'address' => $this->address,

            'services' => $this->services,

            'images' => $this->images,

            'offers' => $this->offers,
        ];

        if (
            in_array($this->status, [
                'accepted',
                'cancelled',
                'completion_requested',
                'completed',
            ]) &&
            $this->acceptedOffer?->worker
        ) {
            $data['worker'] = new WorkerResource($this->acceptedOffer->worker);
        }

        return $data;
            }   
    
    }

