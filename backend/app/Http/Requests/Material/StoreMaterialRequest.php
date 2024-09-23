<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
            'name' => 'required|string',
            'unit' => 'required|string',
            'quantity' => 'required|numeric',
            'material_img' => 'nullable|string',
            'status' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nguyên vật liệu không được để trống',
            'name.string' => 'Tên nguyên vật liệu phải là chuỗi',
            'unit.required' => 'Đơn vị không được để trống',
            'unit.string' => 'Đơn vị phải là chuỗi',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.numeric' => 'Số lượng phải là số',
            'status.required' => 'Trạng thái không được để trống',
            'status.numeric' => 'Trạng thái phải là số',
        ];
    }
}
