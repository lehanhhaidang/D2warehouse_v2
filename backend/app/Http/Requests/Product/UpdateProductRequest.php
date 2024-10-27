<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'category_id' => 'required|integer|exists:categories,id',
            'color_id' => 'required|integer',
            'unit' => 'required|string',
            'quantity' => 'required|integer',
            // 'product_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_img' => 'string|nullable',
            'status' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'category_id.required' => 'Danh mục sản phẩm không được để trống',
            'category_id.integer' => 'Danh mục sản phẩm phải là số nguyên',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại',
            'color_id.required' => 'Màu sắc sản phẩm không được để trống',
            'color_id.integer' => 'Màu sắc sản phẩm phải là số nguyên',
            'unit.required' => 'Đơn vị sản phẩm không được để trống',
            'quantity.required' => 'Số lượng sản phẩm không được để trống',
            'quantity.integer' => 'Số lượng sản phẩm phải là số nguyên',
            'status.required' => 'Trạng thái sản phẩm không được để trống',
            'status.integer' => 'Trạng thái sản phẩm phải là số nguyên',
            // 'product_img.image' => 'File tải lên phải là hình ảnh hợp lệ',
            // 'product_img.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg',
            // 'product_img.max' => 'Hình ảnh tải lên không được vượt quá 2MB',
            'product_img.string' => 'Hình ảnh sản phẩm phải là chuỗi',

        ];
    }
}
