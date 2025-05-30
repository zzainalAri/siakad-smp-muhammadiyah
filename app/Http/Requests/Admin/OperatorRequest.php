<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole(
            'Admin'
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
                Rule::unique('users')->ignore($this->operator?->user),
            ],
            'password' => $this->routeIs('admin.operators.store')
                ? ['required', 'string', 'min:4', 'max:255']
                : ['nullable', 'string', 'min:4', 'max:255'],
            'faculty_id' => [
                'required',
                'exists:faculties,id'
            ],
            'employee_number' => $this->routeIs('admin.operators.store') ? 'required|string|max:13|unique:operators' : 'required|string|max:13',
            'avatar' => 'nullable|mimes:png,jpg,webp,jpeg',

        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'password',
            'faculty_id' => 'Fakultas',
            'employee_number' => 'Nomor Induk Karyawan',
            'avatar' => 'Avatar'
        ];
    }
}
