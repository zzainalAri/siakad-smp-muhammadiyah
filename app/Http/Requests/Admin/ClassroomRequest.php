<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
            'level_id' => 'required|exists:levels,id',
            'academic_year_id' => 'required|exists:academic_years,name',
            'name' => $this->routeIs('admin.classrooms.store') ? 'required|string|max:255|unique:classrooms' : 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'level_id' => 'Tingkat',
            'academic_year_id' => 'Tahun Ajaran',
            'name' => 'Nama Kelas',
        ];
    }
}
