<?php

namespace App\Http\Requests\ProductReceipt;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use App\Models\Shelf;

class StoreProductReceiptRequest extends FormRequest
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
            'receive_date' => 'required|date',
            'status' => 'nullable|integer|in:0,1',
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
                }
            ],
            'propose_id' => 'nullable|exists:proposes,id'
        ];
    }


    public function messages()
    {
        return [
            'details.*.product_id.exists' => 'Sản phẩm không tồn tại.',
            'details.*.shelf_id.exists' => 'Kệ không tồn tại.',
            'details.*.quantity.min' => 'Số lượng sản phẩm phải lớn hơn 0.',
            'details.*.quantity.required' => 'Số lượng sản phẩm không được để trống.',
            'details.*.unit.required' => 'Đơn vị tính không được để trống.',
            'details.*.shelf_id.required' => 'Kệ không được để trống.',
            'details.*.product_id.required' => 'Sản phẩm không được để trống.',
            'details.required' => 'Chi tiết sản phẩm không được để trống.',
            'status.integer' => 'Trạng thái phải là số.',
            'receive_date.required' => 'Ngày nhận không được để trống.',
            'receive_date.date' => 'Ngày nhận không đúng định dạng.',
            'warehouse_id.exists' => 'Kho không tồn tại.',
            'warehouse_id.required' => 'Kho không được để trống.',
            'name.required' => 'Tên phiếu không được để trống.',
            'name.string' => 'Tên phiếu phải là chuỗi.',
            'propose_id.exists' => 'Đề xuất không tồn tại.'
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
