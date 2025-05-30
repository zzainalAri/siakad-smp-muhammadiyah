<?php

namespace App\Http\Requests\Admin;

use App\Enums\ScheduleDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ScheduleRequest extends FormRequest
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
            'faculty_id' => 'required|exists:faculties,id',
            'course_id' => 'required|exists:courses,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'day_of_week' => [
                'required',
                new Enum(ScheduleDay::class)
            ],
            'quote' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'faculty_id' => 'Fakultas',
            'course_id' => 'Mata Kuliah',
            'classroom_id' => 'Kelas',
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Selesai',
            'day_of_week' => 'Hari',
            'quote' => 'Kuota'
        ];
    }
}
