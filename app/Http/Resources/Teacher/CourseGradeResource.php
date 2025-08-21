<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseGradeResource extends JsonResource
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
            'student_id' => $this->student_id,
            'grade' => $this->grade,
            'section_id' => $this->section_id,
            'section' => $this->whenLoaded('section', [
                'id' => $this->section?->id,
                'meeting_number' => $this->section?->meeting_number,
                'meeting_date' => $this->section?->meeting_date,
                'schedule_id' => $this->section?->schedule_id,
            ]),
            'category' => $this->category,
            'created_at' => $this->created_at,
        ];
    }
}
