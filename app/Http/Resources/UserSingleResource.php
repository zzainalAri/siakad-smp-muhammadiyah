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
                'nisn' => $this->student?->nisn,
                'batch' => $this->student?->batch,
                'semester' => $this->student?->semester,
                'faculty' => [
                    'id' => $this->student?->faculty?->id,
                    'name' => $this->student?->faculty?->name,
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
                'nip' => $this->teacher?->nip,
                'academic_title' => $this->teacher?->academic_title,
                'level_id' => $this->teacher?->level_id,
            ]),
            'operator' => $this->when($this->hasRole('Operator'), [
                'id' => $this->operator?->id,
                'employee_number' => $this->operator?->employee_number,
                'level_id' => $this->operator?->level_id,
                'faculty' => $this->operator?->faculty?->name,
            ]),
        ];
    }
}
