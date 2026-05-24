<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'balance' => $this->balance,
            'total_charged' => $this->total_charged,
            'total_spent' => $this->total_spent,
            'status' => $this->status,
        ];
    }
}
