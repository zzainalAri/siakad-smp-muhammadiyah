<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentOperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole(
            'Operator'
        );
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

            'fee_group_id' => [
                'required',
                'exists:fee_groups,id'
            ],
            'classroom_id' => [
                'required',
                'exists:classrooms,id'
            ],
            'student_number' => $this->routeIs('operators.students.store') ? 'required|string|max:13|unique:students' : 'required|string|max:13',
            'semester' => 'required|integer|',
            'batch' => 'required|integer|',
            'avatar' => 'nullable|mimes:png,jpg,webp',

        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'password',
            'fee_group_id' => 'Golongan UKT',
            'student_number' => 'Nomor Induk Mahasiswa',
            'batch' => 'Angkatan',
            'classroom_id' => 'Kelas',
        ];
    }
}
