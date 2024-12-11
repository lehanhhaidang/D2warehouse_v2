<?php

namespace App\Http\Requests\ManufacturingPlan;

use Illuminate\Foundation\Http\FormRequest;

class StoreManufacturingPlanRequest extends FormRequest
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
            'name' => 'required|string|max:255', // Tên kế hoạch sản xuất
            'description' => 'required|string|max:255', // Mô tả kế hoạch sản xuất

            // Kiểm tra mảng manufacturing_plan_details
            'manufacturing_plan_details' => 'required|array|min:1', // Mảng kế hoạch chi tiết phải có ít nhất 1 item
            'manufacturing_plan_details.*.product_id' => 'required|exists:products,id|numeric', // Kiểm tra sản phẩm phải tồn tại trong DB và là kiểu số
            'manufacturing_plan_details.*.product_quantity' => 'required|numeric|min:0|regex:/^([0-9]*[02468])$/', // Số lượng sản phẩm phải là số và >= 0
            'manufacturing_plan_details.*.material_id' => 'required|exists:materials,id|numeric', // Kiểm tra nguyên vật liệu phải tồn tại trong DB và là kiểu số
            'manufacturing_plan_details.*.material_quantity' => 'required|numeric|min:0|regex:/^([0-9]*[02468])$/', // Kiểm tra số lượng nguyên vật liệu phải là số chẵn
        ];
    }

    /**
     * Định nghĩa các thông báo lỗi tuỳ chỉnh.
     */
    public function messages(): array
    {
        return [
            // Thông báo lỗi cho trường 'name'
            'name.required' => 'Vui lòng nhập tên kế hoạch sản xuất.',
            'name.string' => 'Tên kế hoạch sản xuất phải là một chuỗi ký tự.',
            'name.max' => 'Tên kế hoạch sản xuất không được vượt quá 255 ký tự.',

            // Thông báo lỗi cho trường 'description'
            'description.required' => 'Vui lòng nhập mô tả kế hoạch sản xuất.',
            'description.string' => 'Mô tả kế hoạch sản xuất phải là một chuỗi ký tự.',
            'description.max' => 'Mô tả kế hoạch sản xuất không được vượt quá 255 ký tự.',

            // Thông báo lỗi cho mảng 'manufacturing_plan_details'
            'manufacturing_plan_details.required' => 'Vui lòng thực hiện tính toán nguyên vật liệu.',
            'manufacturing_plan_details.array' => 'Chi tiết kế hoạch sản xuất phải là một mảng.',
            'manufacturing_plan_details.min' => 'Chi tiết kế hoạch sản xuất phải có ít nhất một mục.',

            // Thông báo lỗi cho 'product_id' trong mảng 'manufacturing_plan_details'
            'manufacturing_plan_details.*.product_id.required' => 'Vui lòng chọn sản phẩm.',
            'manufacturing_plan_details.*.product_id.exists' => 'Sản phẩm không tồn tại trong hệ thống.',
            'manufacturing_plan_details.*.product_id.numeric' => 'ID sản phẩm phải là một số.',

            // Thông báo lỗi cho 'product_quantity' trong mảng 'manufacturing_plan_details'
            'manufacturing_plan_details.*.product_quantity.required' => 'Vui lòng nhập số lượng sản phẩm.',
            'manufacturing_plan_details.*.product_quantity.numeric' => 'Số lượng sản phẩm phải là một số.',
            'manufacturing_plan_details.*.product_quantity.min' => 'Số lượng sản phẩm phải lớn hơn hoặc bằng 0.',
            'manufacturing_plan_details.*.product_quantity.regex' => 'Số lượng thành phẩm phải là số chẵn.',

            // Thông báo lỗi cho 'material_id' trong mảng 'manufacturing_plan_details'
            'manufacturing_plan_details.*.material_id.required' => 'Vui lòng chọn nguyên vật liệu.',
            'manufacturing_plan_details.*.material_id.exists' => 'Nguyên vật liệu không tồn tại trong hệ thống.',
            'manufacturing_plan_details.*.material_id.numeric' => 'ID nguyên vật liệu phải là một số.',

            // Thông báo lỗi cho 'material_quantity' trong mảng 'manufacturing_plan_details'
            'manufacturing_plan_details.*.material_quantity.required' => 'Vui lòng nhập số lượng nguyên vật liệu.',
            'manufacturing_plan_details.*.material_quantity.numeric' => 'Số lượng nguyên vật liệu phải là một số.',
            'manufacturing_plan_details.*.material_quantity.min' => 'Số lượng nguyên vật liệu phải lớn hơn hoặc bằng 0.',
            'manufacturing_plan_details.*.material_quantity.regex' => 'Số lượng nguyên vật liệu phải là số chẵn.',
        ];
    }
}
