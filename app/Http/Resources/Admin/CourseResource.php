<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'semester' => $this->semester,
            'created_at' => $this->created_at,
            'level' => $this->whenLoaded('level', [
                'id' => $this->level?->id,
                'name' => $this->level?->name,
            ]),
            'teacher' => $this->whenLoaded('teacher', [
                'id' => $this->teacher?->id,
                'name' => $this->teacher?->user?->name,
            ]),
        ];
    }
}