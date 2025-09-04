<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
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
            'amount' => $this->amount,
            'status' => $this->status,
            'semester' => $this->semester,
            'billing_date' => $this->billing_date,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            // 'student' => $this->whenLoaded('student', [
            //     'id' => $this->student?->id,
            //     'nisn' => $this->student?->nisn,
            //     'name' => $this->student?->user?->name,
            //     'level' => $this->student?->level?->name,
            //     'classroom' => $this->student?->classroom?->name,
            // ]),
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ]),
            'feeGroup' => $this->whenLoaded('feeGroup', [
                'id' => $this->feeGroup?->id,
                'amount' => $this->feeGroup?->amount,
                'level_name' => $this->feeGroup?->level?->name,
            ]),
        ];
    }
}
