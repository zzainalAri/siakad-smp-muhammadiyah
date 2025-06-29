<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\Operator\GradeOperatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudyResultStudentResource extends JsonResource
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
            'semester' => $this->semester,
            'gpa' => $this->gpa,
            'created_at' => $this->created_at,
            'student' => $this->whenLoaded('student', [
                'id' => $this->student?->id,
                'name' => $this->student?->user?->name,
                'nisn' => $this->student?->nisn,
                'avatar' => $this->student?->user?->avatar ? Storage::url($this->student?->user?->avatar) : null,
            ]),
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
            'grades' => GradeOperatorResource::collection($this->grades),
        ];
    }
}
