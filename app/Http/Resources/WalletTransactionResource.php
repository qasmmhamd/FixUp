<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray($request)
    {
        return [
            'type' => $this->type,
            'amount' => $this->amount,
            'before' => $this->balance_before,
            'after' => $this->balance_after,
            'note' => $this->note,
            'created_at' => $this->created_at
        ];
    }

}
