<?php

namespace App\Http\Requests\Operator;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StudentOperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Operator');
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
            'password' => $this->routeIs('operators.students.store')
                ? ['required', 'string', 'min:4', 'max:255']
                : ['nullable', 'string', 'min:4', 'max:255'],
            'classroom_id' => [
                'required',
                'exists:classrooms,id'
            ],
            'nisn' => $this->routeIs('operators.students.store') ? 'required|string|max:13|unique:students' : 'required|string|max:13',
            'batch' => 'required|integer|',
            'avatar' => 'nullable|mimes:png,jpg,webp',
            'status' => ['required', new Enum(StudentStatus::class)],
            'gender' => ['required', new Enum(Gender::class)],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'classroom_id' => 'Kelas',
            'nisn' => 'NISN',
            'batch' => 'Angkatan',
            'avatar' => 'Avatar',
            'status' => 'Status',
            'gender' => 'Jenis Kelamin',
        ];
    }
}