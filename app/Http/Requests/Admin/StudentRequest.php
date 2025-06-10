<?php

namespace App\Http\Requests\Admin;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  auth()->check() && auth()->user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->student?->user),
            ],
            'password' => $this->routeIs('admin.students.store')
                ? ['required', 'string', 'min:4', 'max:255']
                : ['nullable', 'string', 'min:4', 'max:255'],
            'level_id' => [
                'required',
                'exists:levels,id'
            ],
            'classroom_id' => [
                'required',
                'exists:classrooms,id'
            ],
            'nisn' => $this->routeIs('admin.students.store') ? 'required|string|max:13|unique:students' : 'required|string|max:13',
            'batch' => 'required|integer|',
            'avatar' => 'nullable|mimes:png,jpg,webp',
            'gender' => ['required', new Enum(Gender::class)],
            'status' => ['required', new Enum(StudentStatus::class)],
            'address' => 'required|string|max:500',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'password',
            'level_id' => 'Tingkat',
            'nisn' => 'NISN',
            'batch' => 'Angkatan',
            'classroom_id' => 'Kelas',
            'gender' => 'Jenis Kelamin',
            'status' => 'Status',
            'address' => 'Alamat',
        ];
    }
}