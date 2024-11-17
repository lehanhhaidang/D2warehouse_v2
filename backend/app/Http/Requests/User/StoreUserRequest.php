<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:258',
            'email' => 'required|string',
            'phone' => 'required|string',
            'password' => 'string',
            'role_id' => 'required|exists:roles,id',
            'img_url' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is requried',
            'password.required' => 'Password is required',
            'role_id.required' => 'Role is required',
            'img_url.string' => 'Image url must be string',
            'role_id.exists' => 'Role does not exist',
            'phone.required' => 'Phone is required',

        ];
    }
}
