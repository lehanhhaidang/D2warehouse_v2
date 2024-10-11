<?php

namespace App\Http\Requests\Shelf;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShelfRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'number_of_levels' => 'required|integer',
            'storage_capacity' => 'required|integer',
        ];
    }



    public function messages()
    {
        return [
            'name.required' => 'Tên kệ hàng không được để trống',
            'name.string' => 'Tên kệ hàng phải là chuỗi',
            'number_of_levels.required' => 'Số tầng không được để trống',
            'number_of_levels.integer' => 'Số tầng phải là số nguyên',
            'storage_capacity.required' => 'Sức chứa không được để trống',
            'storage_capacity.integer' => 'Sức chứa phải là số nguyên',
        ];
    }
}
