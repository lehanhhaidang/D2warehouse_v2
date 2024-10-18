<?php

namespace App\Http\Requests\ProductExport;

use App\Models\Product;
use App\Models\Shelf;
use App\Models\ShelfDetail;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductExportRequest extends FormRequest
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
            'name' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'export_date' => 'required|date',
            'status' => 'required|integer',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.shelf_id' => 'required|exists:shelves,id',
            'details.*.unit' => 'required|string',
            'details.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value % 100 !== 0) {
                        $fail('Số lượng sản phẩm phải là bội số của 100.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên phiếu xuất không được để trống.',
            'name.string' => 'Tên phiếu xuất phải là chuỗi ký tự.',
            'warehouse_id.required' => 'Kho không được để trống.',
            'warehouse_id.exists' => 'Kho không tồn tại.',
            'export_date.required' => 'Ngày xuất không được để trống.',
            'export_date.date' => 'Ngày xuất không đúng định dạng.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.integer' => 'Trạng thái phải là số nguyên.',
            'details.required' => 'Chi tiết xuất không được để trống.',
            'details.array' => 'Chi tiết xuất phải là mảng.',
            'details.*.product_id.required' => 'Sản phẩm không được để trống.',
            'details.*.product_id.exists' => 'Sản phẩm không tồn tại.',
            'details.*.shelf_id.required' => 'Kệ không được để trống.',
            'details.*.shelf_id.exists' => 'Kệ không tồn tại.',
            'details.*.unit.required' => 'Đơn vị không được để trống.',
            'details.*.unit.string' => 'Đơn vị phải là chuỗi ký tự.',
            'details.*.quantity.min' => 'Số lượng xuất phải lớn hơn 0.',
            'details.*.quantity.integer' => 'Số lượng xuất phải là số nguyên.',
            'details.*.quantity.required' => 'Số lượng xuất không được để trống.',
            'details.*.quantity.exists' => 'Số lượng xuất phải nhỏ hơn hoặc bằng số lượng hiện có trên kệ.'

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->details as $detail) {
                $product = Product::find($detail['product_id']);
                $shelf = Shelf::find($detail['shelf_id']);

                if ($product->category_id !== $shelf->category_id) {
                    $validator->errors()->add('details.' . $detail['product_id'], 'Sản phẩm và kệ không có cùng loại danh mục. ');
                }
            }
        });
    }
}
