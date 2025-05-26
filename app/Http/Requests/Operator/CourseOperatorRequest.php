<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;

class CourseOperatorRequest extends FormRequest
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
            'teacher_id' => 'required|exists:users,id',
            'name' => 'required|string|min:3|max:255',
            'credit' => 'required|integer',
            'semester' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'teacher_id' => 'Dosen',
            'name' => 'Nama',
            'credit' => 'Satuan Kredit Semester (SKS)',
            'semester' => 'Semester'
        ];
    }
}
