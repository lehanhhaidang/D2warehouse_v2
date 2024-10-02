<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Warehouse;
use App\Models\Category;

class StoreShelfRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Hoặc bạn có thể kiểm tra quyền truy cập ở đây
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
            'number_of_levels' => 'required|integer',
            'storage_capacity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ];
    }






    public function messages()
    {
        return [
            'name.required' => 'Tên kệ hàng không được để trống',
            'name.string' => 'Tên kệ hàng phải là chuỗi',
            'warehouse_id.required' => 'Kho không được để trống',
            'warehouse_id.integer' => 'Kho phải là số nguyên',
            'number_of_levels.required' => 'Số tầng không được để trống',
            'number_of_levels.integer' => 'Số tầng phải là số nguyên',
            'storage_capacity.required' => 'Sức chứa không được để trống',
            'storage_capacity.integer' => 'Sức chứa phải là số nguyên',
            'category_id.required' => 'Danh mục không được để trống',
            'category_id.integer' => 'Danh mục phải là số nguyên',
        ];
    }



    public function withValidator($validator)
    {

        // Kiểm tra xem danh mục kệ hàng có phù hợp với kho không và ngược lại

        $validator->after(function ($validator) {
            $warehouse = Warehouse::find($this->warehouse_id);
            $warehouseCategory = $warehouse->category_id;
            $warehouseCategoryName = Category::find($warehouseCategory)->name;

            $newCategory = Category::find($this->category_id);

            if ($warehouseCategoryName === 'Product' && $newCategory->type !== 'product') {
                $validator->errors()->add('category_id', 'Danh mục bạn đã chọn không phù hợp với kho này. Hãy chọn danh mục thuộc thành phẩm');
            }

            if ($warehouseCategoryName !== 'Product' && $newCategory->type === 'product') {
                $validator->errors()->add('category_id', 'Danh mục bạn đã chọn không phù hợp với kho này. Hãy chọn danh mục thuộc nguyên vật liệu');
            }
        });
    }
}
