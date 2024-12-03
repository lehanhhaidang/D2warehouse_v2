<?php

namespace App\Http\Requests\MaterialExport;

use App\Models\Material;
use App\Models\Shelf;
use App\Models\ShelfDetail;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialExportRequest extends FormRequest
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
            'status' => 'nullable|integer',
            'details' => 'required|array',
            'details.*.material_id' => 'required|exists:materials,id',
            'details.*.shelf_id' => 'required|exists:shelves,id',
            'details.*.unit' => 'required|string',
            'details.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value % 100 !== 0) {
                        $fail('Số lượng nguyên vật liệu phải là bội số của 100.');
                    }
                },
            ],
            'propose_id' => 'required|exists:proposes,id',
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
            'status.integer' => 'Trạng thái phải là số nguyên.',
            'details.required' => 'Chi tiết xuất không được để trống.',
            'details.array' => 'Chi tiết xuất phải là mảng.',
            'details.*.material_id.required' => 'Nguyên vật liệu không được để trống.',
            'details.*.material_id.exists' => 'Nguyên vật liệu không tồn tại.',
            'details.*.shelf_id.required' => 'Kệ không được để trống.',
            'details.*.shelf_id.exists' => 'Kệ không tồn tại.',
            'details.*.unit.required' => 'Đơn vị không được để trống.',
            'details.*.unit.string' => 'Đơn vị phải là chuỗi ký tự.',
            'details.*.quantity.min' => 'Số lượng xuất phải lớn hơn 0.',
            'details.*.quantity.integer' => 'Số lượng xuất phải là số nguyên.',
            'details.*.quantity.required' => 'Số lượng xuất không được để trống.',
            'details.*.quantity.exists' => 'Số lượng xuất phải nhỏ hơn hoặc bằng số lượng hiện có trên kệ.',
            'propose_id.required' => 'Đề xuất không được để trống.',

        ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         foreach ($this->details as $detail) {
    //             $Material = Material::find($detail['material_id']);
    //             $shelf = Shelf::find($detail['shelf_id']);

    //             if ($Material->category_id !== $shelf->category_id) {
    //                 $validator->errors()->add('details.' . $detail['material_id'], 'Nguyên vật liệu và kệ không có cùng loại danh mục. ');
    //             }
    //         }
    //     });
    // }
}
