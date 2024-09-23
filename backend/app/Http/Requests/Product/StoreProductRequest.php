<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255', // Giới hạn độ dài tên sản phẩm
            'category_id' => 'required|integer',
            'color_id' => 'required|integer',
            'unit' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1', // Số lượng tối thiểu là 1
            'product_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|integer|in:0,1', // Trạng thái phải là 0 hoặc 1
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'category_id.required' => 'Danh mục sản phẩm không được để trống',
            'category_id.integer' => 'Danh mục sản phẩm phải là số nguyên',
            'color_id.required' => 'Màu sắc sản phẩm không được để trống',
            'color_id.integer' => 'Màu sắc sản phẩm phải là số nguyên',
            'unit.required' => 'Đơn vị sản phẩm không được để trống',
            'unit.max' => 'Đơn vị sản phẩm không được vượt quá 100 ký tự',
            'quantity.required' => 'Số lượng sản phẩm không được để trống',
            'quantity.integer' => 'Số lượng sản phẩm phải là số nguyên',
            'quantity.min' => 'Số lượng sản phẩm phải lớn hơn hoặc bằng 1',
            'product_img.image' => 'File tải lên phải là hình ảnh hợp lệ',
            'product_img.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg',
            'product_img.max' => 'Hình ảnh tải lên không được vượt quá 2MB',
            'status.required' => 'Trạng thái sản phẩm không được để trống',
            'status.integer' => 'Trạng thái sản phẩm phải là số nguyên',
            'status.in' => 'Trạng thái sản phẩm phải là 0 hoặc 1',
        ];
    }
}
