<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequests extends FormRequest
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
            'name' => 'required|max:255',
            'business_place' => 'required',
            'primary_category' => 'required',
            'business_website' => 'required',
            'phone_number' => 'required',
            'business_time' => 'required',
            'email' => ['required','email:rfc,dns', Rule::unique('users', 'email')->ignore($this->id)],
            'description' => 'required',
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
            'name.required' => 'Business name field is required',
            'business_place.required' => 'Business place field is required',
            'primary_category.required' => 'Primary category fieldis required',
            'business_website.required' => 'Business website field is required',
            'phone_number.required' => 'Phone number field is required',
            'email.required'       => 'Email field is required',
            'email.email'          => 'Enter valid email',
            'description.required' => 'Description field is required',
            'business_time.required' => 'Business time field is required',
        ];
    }
}
