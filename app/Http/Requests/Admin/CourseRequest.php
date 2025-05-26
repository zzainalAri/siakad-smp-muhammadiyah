<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'faculty_id' => 'required|exists:faculties,id',
            'departement_id' => 'required|exists:departements,id',
            'teacher_id' => 'required|exists:users,id',
            'name' => 'required|string|min:3|max:255',
            'credit' => 'required|integer',
            'semester' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'faculty_id' => 'Fakultas',
            'departement_id' => 'Program Studi',
            'teacher_id' => 'Dosen',
            'name' => 'Nama',
            'credit' => 'Satuan Kredit Semester (SKS)',
            'semester' => 'Semester'
        ];
    }
}
