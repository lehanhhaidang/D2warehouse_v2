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
            'phone' => 'required|string|unique:users,phone,' . $this->route('id'),
            'role_id' => 'required|exists:roles,id',
            'warehouse_ids' => 'nullable|array',
            'warehouse_ids.*' => 'exists:warehouses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống',
            'email.unique' => 'Email đã tồn tại',
            'email.required' => 'Email không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'role_id.required' => 'Vai trò không được để trống',
            'img_url.string' => 'Hình ảnh phải là chuỗi',
            'warehouse_ids.array' => 'Warehouse ids must be an array',
            'warehouse_ids.*.exists' => 'Warehouse id is invalid',


        ];
    }
}
