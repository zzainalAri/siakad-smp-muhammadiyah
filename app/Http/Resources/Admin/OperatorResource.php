<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OperatorResource extends JsonResource
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
            'employee_number' => $this->employee_number,
            'created_at' => $this->created_at,
            'user' => $this->whenLoaded('user', [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'avatar' => $this->user?->avatar ? Storage::url($this->user?->avatar) : null,
            ]),
            'faculty' => $this->whenLoaded('faculty', [
                'id' => $this->faculty?->id,
                'name' => $this->faculty?->name,
            ]),
            'departement' => $this->whenLoaded('departement', [
                'id' => $this->departement?->id,
                'name' => $this->departement?->name,
            ]),
        ];
    }
}
