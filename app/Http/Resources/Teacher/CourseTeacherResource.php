<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseTeacherResource extends JsonResource
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
            'created_at' => $this->created_at,
            'level' => $this->whenLoaded('level', [
                'id' => $this->level?->id,
                'name' => $this->level?->name,
            ]),

        ];
    }
}
