<?php
// app/Http/Resources/TransactionResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date->toDateString(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
