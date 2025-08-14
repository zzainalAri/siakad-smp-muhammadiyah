<?php

namespace App\Http\Requests\Admin;

use App\Enums\Gender;
use App\Enums\StudentRegistrationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StudentRegistrationRequest extends FormRequest
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
            'nisn' => [
                'required',
                'min:10',
                'max:10',
                'regex:/^\d{10,10}$/',
                Rule::unique('student_registrations')->ignore($this->studentRegistration),
            ],
            'nik' => [
                'required',
                'min:16',
                'max:16',
                'regex:/^\d{16,16}$/',
                Rule::unique('student_registrations')->ignore($this->studentRegistration),
            ],
            'birth_place' => 'required|max:255',
            'birth_date' => 'required',
            'previous_school' => 'required',
            'phone' => [
                'required',
            ],
            'gender' => ['required', new Enum(Gender::class)],
            'status' => ['required', new Enum(StudentRegistrationStatus::class)],
            'address' => 'required|string|max:255',
            'doc_kk' => $this->routeIs('admin.student-registrations.store') ? 'required|image|mimes:png,jpg|max:4096' : 'nullable|mimes:png,jpg|max:4096',
            'doc_akta' =>  $this->routeIs('admin.student-registrations.store') ? 'required|image|mimes:png,jpg|max:4096' : 'nullable|mimes:png,jpg|max:4096',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'nisn' => 'nisn',
            'previous_school' => 'Nama Sekolah Dasar Sebelumnya',
            'nik' => 'nik',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'status' => 'Status',
            'address' => 'Alamat',
            'phone' => 'No Hp',
            'doc_kk' => 'Kartu Keluarga',
            'doc_akta' => 'AKta Kelahiran',
        ];
    }
}
