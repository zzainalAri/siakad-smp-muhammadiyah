<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherOperatorRequest extends FormRequest
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
                Rule::unique('users')->ignore($this->teacher?->user),
            ],
            'password' => $this->routeIs('operators.teachers.store')
                ? ['required', 'string', 'min:4', 'max:255']
                : ['nullable', 'string', 'min:4', 'max:255'],
            'nip' => $this->routeIs('operators.teachers.store') ? 'required|string|max:13|unique:teachers' : 'required|string|max:13',
            'academic_title' => 'required|string|min:3|max:255',
            'avatar' => 'nullable|mimes:png,jpg,webp,jpeg',

        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'password',
            'nip' => 'Nomor Induk Pegawai / NIP',
            'academic_title' => 'Jabatan Akademik',
            'avatar' => 'Avatar'
        ];
    }
}
