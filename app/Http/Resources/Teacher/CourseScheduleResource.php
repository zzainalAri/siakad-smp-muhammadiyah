<?php

namespace App\Http\Resources\Teacher;

use App\Http\Resources\Admin\ScheduleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseScheduleResource extends JsonResource
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
            'credit' => $this->credit,
            'semester' => $this->semester,
            'created_at' => $this->created_at,
            'faculty' => $this->whenLoaded('faculty', [
                'id' => $this->faculty?->id,
                'name' => $this->faculty?->name,
            ]),
            'departement' => $this->whenLoaded('departement', [
                'id' => $this->departement?->id,
                'name' => $this->departement?->name,
            ]),
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
            'schedules' => ScheduleResource::collection($this->schedules),
        ];
    }
}
