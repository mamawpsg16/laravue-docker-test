<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow anyone to make this request (e.g., guests)
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|max:20|confirmed',
            'password_confirmation'      => 'required|string|min:8|max:20',
            'birth_date'    => 'required|date',
            'gender'        => 'required|in:male,female,other',
            'address'        => 'nullable|string|max:500',
        ];
    }
}
