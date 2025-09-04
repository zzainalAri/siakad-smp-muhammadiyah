<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeGroupResource extends JsonResource
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
            'level_id' => $this->level_id,
            'amount' => $this->amount,
            'level' => $this->whenLoaded('level', [
                'id' => $this->level?->id,
                'name' => $this->level?->name,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
