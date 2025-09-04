<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'password' => $this->routeIs('admin.teachers.store')
                ? ['required', 'string', 'min:4', 'max:255']
                : ['nullable', 'string', 'min:4', 'max:255'],
            'level_id' => [
                'required',
                'exists:levels,id'
            ],
            'nip' => $this->routeIs('admin.teachers.store') ? 'required|string|max:13|unique:teachers,nip' : 'required|string|max:13',
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
            'level_id' => 'Tingkat',
            'nip' => 'NIP',
            'academic_title' => 'Jabatan Akademik',
            'avatar' => 'Avatar'
        ];
    }
}
