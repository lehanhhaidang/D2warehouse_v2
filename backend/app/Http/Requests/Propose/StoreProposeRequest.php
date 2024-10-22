<?php

namespace App\Http\Requests\Propose;

use App\Enum\ProposeStatus;
use App\Models\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposeRequest extends FormRequest
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
            'type' => 'required|string',
            'name' => 'required|string',
            'status' => 'required|in:' . implode(',', array_column(ProposeStatus::cases(), 'value')),
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'description' => 'required|string',
            'details' => 'required|array',
            'details.*.quantity' => 'required|integer',
            'details.*.unit' => 'required|string',
            'details.*.product_id' => 'nullable|integer', // Thay đổi ở đây
            'details.*.material_id' => 'nullable|integer', // Thêm vào đây
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Loại đề xuất không được để trống',
            'type.string' => 'Loại đề xuất phải là chuỗi',
            'name.required' => 'Tên đề xuất không được để trống',
            'name.string' => 'Tên đề xuất phải là chuỗi',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'warehouse_id.required' => 'Kho không được để trống',
            'warehouse_id.integer' => 'Kho phải là số nguyên',
            'warehouse_id.exists' => 'Kho không tồn tại',
            'description.required' => 'Mô tả không được để trống',
            'description.string' => 'Mô tả phải là chuỗi',
            'created_by.required' => 'Người tạo không được để trống',
            'created_by.integer' => 'Người tạo phải là số nguyên',
            'details.required' => 'Chi tiết đề xuất không được để trống',
            'details.array' => 'Chi tiết đề xuất phải là mảng',
            'details.*.product_id.required' => 'Sản phẩm không được để trống',
            'details.*.product_id.integer' => 'Sản phẩm phải là số nguyên',
            'details.*.quantity.required' => 'Số lượng không được để trống',
            'details.*.quantity.integer' => 'Số lượng phải là số nguyên',
            'details.*.unit.required' => 'Đơn vị không được để trống',
            'details.*.unit.string' => 'Đơn vị phải là chuỗi',
            'details.*.status.required' => 'Trạng thái không được để trống',
            'details.*.status.string' => 'Trạng thái phải là chuỗi',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $warehouseId = $this->warehouse_id;

            // Lấy kho từ ID
            $warehouse = Warehouse::find($warehouseId);

            if (!$warehouse) {
                return; // Nếu kho không tồn tại, không cần kiểm tra
            }

            // Lấy category_id từ kho
            $categoryId = $warehouse->category_id; // Giả sử có category_id trong Warehouse

            foreach ($this->details as $index => $detail) {
                if ($categoryId === 1) {
                    // Nếu category_id là 1, chỉ cho phép material_id
                    if (empty($detail['material_id'])) {
                        $validator->errors()->add("details.$index.material_id", 'Vui lòng chọn nguyên vật liệu.');
                    }
                    if (!empty($detail['product_id'])) {
                        $validator->errors()->add("details.$index.product_id", 'Không thể chọn thành phẩm cho kho nguyên vật liệu.');
                    }
                } elseif ($categoryId === 2) {
                    // Nếu category_id là 2, chỉ cho phép product_id
                    if (empty($detail['product_id'])) {
                        $validator->errors()->add("details.$index.product_id", 'Vui lòng chọn thành phẩm.');
                    }
                    if (!empty($detail['material_id'])) {
                        $validator->errors()->add("details.$index.material_id", 'Không thể chọn nguyên vật liệu cho kho thành phẩm.');
                    }
                }
            }
        });
    }
}
