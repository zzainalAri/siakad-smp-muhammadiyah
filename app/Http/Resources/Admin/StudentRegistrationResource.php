<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudentRegistrationResource extends JsonResource
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
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'nisn' => $this->nisn,
            'nik' => $this->nik,
            'religion' => $this->religion,
            'no_kk' => $this->no_kk,
            'mother_name' => $this->mother_name,
            'father_name' => $this->father_name,
            'mother_nik' => $this->mother_nik,
            'father_nik' => $this->father_nik,
            'previous_school' => $this->previous_school,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'accepted_date' => $this->accepted_date,
        ];
    }
}
