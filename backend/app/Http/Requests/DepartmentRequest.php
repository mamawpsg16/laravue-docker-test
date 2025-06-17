<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class DepartmentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments', 'name')->ignore($this->department),
            ],
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('departments', 'code')->ignore($this->department),
            ],
            'description' => 'nullable|string',
            'head_doctor_id' => 'nullable|string',
            'is_active' => 'boolean',
            'operating_hours' => 'nullable|json',
        ];
    }
}
