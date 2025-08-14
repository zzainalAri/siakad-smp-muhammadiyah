<?php

namespace App\Http\Requests\Admin;

use App\Enums\Gender;
use App\Enums\Religion;
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
            'mother_name' => 'required|string|min:3|max:255',
            'father_name' => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('student_registrations')->ignore($this->studentRegistration),
            ],
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
            'no_kk' => [
                'required',
                'min:16',
                'max:16',
                'regex:/^\d{16,16}$/',
                Rule::unique('student_registrations')->ignore($this->studentRegistration),
            ],
            'mother_nik' => [
                'required',
                'min:16',
                'max:16',
                'regex:/^\d{16,16}$/',
                Rule::unique('student_registrations')->ignore($this->studentRegistration),
            ],
            'father_nik' => [
                'required',
                'min:16',
                'max:16',
                'regex:/^\d{16,16}$/',
                Rule::unique('student_registrations')->ignore($this->studentRegistration),
            ],

            'birth_place' => 'required|max:255',
            'birth_date' => 'required|date',
            'previous_school' => 'required',
            'phone' => [
                'required',
            ],
            'gender' => ['required', new Enum(Gender::class)],
            'religion' => ['required', new Enum(Religion::class)],
            'address' => 'required|string|max:255',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nama',
            'mother_name' => 'Nama Ibu',
            'father_name' => 'Nama Ayah',
            'nisn' => 'nisn',
            'religion' => 'Agama',
            'mother_nik' => 'NIK Ibu',
            'no_kk' => 'Nomer Kartu Keluarga',
            'father_nik' => 'NIK ayah',
            'email' => 'Email',
            'previous_school' => 'Nama Sekolah Dasar Sebelumnya',
            'nik' => 'nik',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat',
            'phone' => 'No Hp',
        ];
    }
}
