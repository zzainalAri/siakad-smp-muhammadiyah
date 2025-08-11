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
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'doc_kk' => $this->doc_kk ? Storage::url($this->doc_kk) : null,
            'doc_akta' => $this->doc_akta ? Storage::url($this->doc_akta) : null,
            'nisn' => $this->nisn,
            'nik' => $this->nik,
            'previous_school' => $this->previous_school,
            'status' => $this->status,
        ];
    }
}
