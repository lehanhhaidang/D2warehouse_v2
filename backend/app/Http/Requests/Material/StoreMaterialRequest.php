<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

use App\Services\ShelfCategoryChecker;

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
            'material_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|numeric',
            'category_id' => 'required|integer',
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
            'material_img.image' => 'Ảnh không đúng định dạng',
            'material_img.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'material_img.max' => 'Ảnh không được vượt quá 2048kb',
            'category_id.required' => 'Danh mục sản phẩm không được để trống',
            'category_id.integer' => 'Danh mục sản phẩm phải là số nguyên',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $checker = new ShelfCategoryChecker();
            if (!$checker->checkShelfBelongsToCategory($this->shelf_id, $this->category_id)) {
                // Lấy danh sách tên kệ và tên kho phù hợp với category_id
                $shelves = $checker->getShelvesByCategory($this->category_id);
                $shelfDetails = $shelves->toArray(); // Chuyển đổi collection thành mảng

                // Tạo thông báo lỗi với gợi ý
                $errorMessage = 'Kệ không thuộc về loại nguyên vật liệu đã chọn. Các kệ hợp lệ: ' . implode(', ', $shelfDetails);
                $validator->errors()->add('shelf_id', $errorMessage);
            }
        });
    }
}
