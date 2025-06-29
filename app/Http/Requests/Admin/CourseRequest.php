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
            'level_id' => 'required|exists:levels,id',
            'teacher_id' => 'required|exists:teachers,id',
            'name' => 'required|string|min:3|max:255',
            'semester' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'level_id' => 'Tingkat',
            'teacher_id' => 'Dosen',
            'name' => 'Nama',
            'semester' => 'Semester'
        ];
    }
}