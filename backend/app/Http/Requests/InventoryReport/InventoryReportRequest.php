<?php

namespace App\Http\Requests\InventoryReport;

use Illuminate\Foundation\Http\FormRequest;

class InventoryReportRequest extends FormRequest
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
            'details.*.actual_quantity' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'details.*.actual_quantity.required' => 'Vui lòng nhập số lượng thực tế',
            'details.*.actual_quantity.numeric' => 'Số lượng thực tế phải là số',
            'details.*.actual_quantity.min' => 'Số lượng thực tế phải lớn hơn hoặc bằng 0',
        ];
    }
}
