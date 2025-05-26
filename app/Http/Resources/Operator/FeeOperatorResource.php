<?php

namespace App\Http\Resources\Operator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FeeOperatorResource extends JsonResource
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
            'fee_code' => $this->fee_code,
            'semester' => $this->semester,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'student' => $this->whenLoaded('student', [
                'id' => $this->student?->id,
                'name' => $this->student?->user?->name,
                'student_number' => $this->student?->user?->student_number,
                'avatar' => $this->student?->user?->avatar ? Storage::url($this->student?->user?->avatar) : null,
            ]),
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
            'feeGroup' => $this->whenLoaded('feeGroup', [
                'id' => $this->feeGroup?->id,
                'group' => $this->feeGroup?->group,
            ]),
        ];
    }
}
