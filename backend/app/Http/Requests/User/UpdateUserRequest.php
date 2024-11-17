<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore($this->route('id')),
            ],
            'password' => 'required|string',
            'img_url' => 'nullable|string',
            'phone' => 'required|string',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.unique' => 'This email has been taken by other user',
            'email.required' => 'Email is requried',
            'password.required' => 'Password is required',
            'phone.required' => 'Phone is required',
            'role_id.required' => 'Role is required',
            'img_url.string' => 'Image url must be string',

        ];
    }
}
