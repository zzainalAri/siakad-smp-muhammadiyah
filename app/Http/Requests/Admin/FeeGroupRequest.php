<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeeGroupRequest extends FormRequest
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
            'group' => [
                'required',
                'integer',
                Rule::unique('fee_groups')->ignore($this->feeGroup),
            ],
            'amount' => 'required|numeric'
        ];
    }

    public function attributes()
    {
        return [
            'group' => 'Golongan UKT',
            'amount' => 'Jumlah'
        ];
    }
}
