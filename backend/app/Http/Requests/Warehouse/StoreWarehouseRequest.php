<?php

namespace App\Http\Requests\Warehouse;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'acreage' => 'required|numeric',
            'number_of_shelves' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên kho không được để trống',
            'name.string' => 'Tên kho phải là chuỗi',
            'location.required' => 'Địa chỉ không được để trống',
            'location.string' => 'Địa chỉ phải là chuỗi',
            'acreage.required' => 'Diện tích không được để trống',
            'acreage.numeric' => 'Diện tích phải là số',
            'number_of_shelves.required' => 'Số kệ hàng không được để trống',
            'number_of_shelves.integer' => 'Số kệ hàng phải là số nguyên',
            'category_id.required' => 'Danh mục không được để trống',
            'category_id.integer' => 'Danh mục phải là số nguyên',
            'category_id.exists' => 'Danh mục không tồn tại',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $category = Category::find($this->category_id);
            if (!$category || ($category->name !== 'Product' && $category->name !== 'Material')) {
                $validator->errors()->add('category_id', 'Danh mục không hợp lệ, hãy chọn danh mục Product hoặc Material');
            }
        });
    }
}
