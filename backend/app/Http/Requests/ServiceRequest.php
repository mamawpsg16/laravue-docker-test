<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:100',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('services', 'code')->ignore($this->service),
            ],
            
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'preparation_instructions' => 'nullable|string',
            'requires_fasting' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
