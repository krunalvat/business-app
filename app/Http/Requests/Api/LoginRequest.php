<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required','email:rfc,dns'],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'       => 'Email is required',
            'email.email'          => 'Enter valid email',
            'password.required' => 'The current password is required.',
            'password.string' => 'The current password must be a string.',
            'password_confirmation.required' => 'The confirmation password is required.',
            'password_confirmation.same' => 'The confirmation password does not match the current password.',
        ];
    }
}
