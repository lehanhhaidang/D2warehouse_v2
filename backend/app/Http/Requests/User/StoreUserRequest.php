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
            'email' => 'required|string|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'string',
            'role_id' => 'required|exists:roles,id',
            'img_url' => 'nullable|string',
            'warehouse_ids' => 'nullable|array',
            'warehouse_ids.*' => 'exists:warehouses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'password.required' => 'Mật khẩu là bắt buộc',
            'role_id.required' => 'Vai trò là bắt buộc',
            'img_url.string' => 'Đường dẫn ảnh phải là chuỗi',
            'role_id.exists' => 'Vai trò không tồn tại',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'warehouse_ids.array' => 'Danh sách kho phải là một mảng',
            'warehouse_ids.*.exists' => 'Kho không tồn tại',
            'email.unique' => 'Email đã được sử dụng',


        ];
    }
}
