<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserSingleResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ? Storage::url($this->avatar) : null,
            'roles' => $this->getRoleNames(),
            'role_name' => $this->getRoleNames()->first(),
            'student' => $this->when($this->hasRole('Student'), [
                'id' => $this->student?->id,
                'student_number' => $this->student?->student_number,
                'batch' => $this->student?->batch,
                'semester' => $this->student?->semester,
                'faculty' => [
                    'id' => $this->student?->faculty?->id,
                    'name' => $this->student?->faculty?->name,
                ],
                'departement' => [
                    'id' => $this->student?->departement?->id,
                    'name' => $this->student?->departement?->name,
                ],
                'classroom' => [
                    'id' => $this->student?->classroom?->id,
                    'name' => $this->student?->classroom?->name,
                ],
                'feeGroup' => [
                    'id' => $this->student?->feeGroup?->id,
                    'group' => $this->student?->feeGroup?->group,
                    'amount' => $this->student?->feeGroup?->amount,
                ],
            ]),
            'teacher' => $this->when($this->hasRole('Teacher'), [
                'id' => $this->teacher?->id,
                'teacher_number' => $this->teacher?->teacher_number,
                'academic_title' => $this->teacher?->academic_title,
                'faculty_id' => $this->teacher?->faculty_id,
                'departement_id' => $this->teacher?->departement_id,
            ]),
            'operator' => $this->when($this->hasRole('Operator'), [
                'id' => $this->operator?->id,
                'employee_number' => $this->operator?->employee_number,
                'faculty_id' => $this->operator?->faculty_id,
                'faculty' => $this->operator?->faculty?->name,
                'departement_id' => $this->operator?->departement_id,
                'departement' => $this->operator?->departement->name,
            ]),
        ];
    }
}
