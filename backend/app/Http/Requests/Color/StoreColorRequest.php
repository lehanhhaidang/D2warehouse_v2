<?php

namespace App\Http\Requests\Color;

use Illuminate\Foundation\Http\FormRequest;

class StoreColorRequest extends FormRequest
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
            'name' => 'required|string|unique:colors,name',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên màu không được để trống',
            'name.string' => 'Tên màu phải là chuỗi',
            'name.unique' => 'Tên màu đã tồn tại',
        ];
    }
}
