<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Repositories\MaterialRepository;
use Illuminate\Support\Facades\Log;

class MaterialService
{
    protected $materialRepository;

    public function __construct(MaterialRepository $materialRepository)
    {
        $this->materialRepository = $materialRepository;
    }

    public function getAllMaterials()
    {
        $products = $this->materialRepository->all();
        if ($products->isEmpty()) {
            throw new \Exception('Hiện tại chưa có nguyên vật liệu nào', 404);
        }
        return $products;
    }


    public function getMaterial($id)
    {
        $product = $this->materialRepository->find($id);
        if (!$product) {
            throw new \Exception('Không tìm thấy nguyên vật liệu', 404);
        }
        return $product;
    }

    public function storeMaterial($request)
    {
        try {
            // Tạo thư mục chứa ảnh nguyên vật liệu nếu chưa tồn tại
            $directory = 'images/materials';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Xử lý lưu ảnh
            $imagePath = null;
            if ($request->hasFile('material_img') && $request->file('material_img')->isValid()) {
                $imagePath = $request->file('material_img')->store($directory, 'public');
            }

            // Dữ liệu nguyên vật liệu
            $data = [
                'name' => $request->name,
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'material_img' => $imagePath,
                'status' => $request->status,
            ];

            // Tạo nguyên vật liệu thông qua repository
            return $this->materialRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm nguyên vật liệu: ' . $e->getMessage());
            throw new \Exception('Thêm nguyên vật liệu thất bại');
        }
    }

    public function updateMaterial($request, $id)
    {
        try {
            $material = $this->materialRepository->find($id);

            if (!$material) {
                throw new \Exception('Không tìm thấy nguyên vật liệu', 404);
            }

            // Logic xử lý ảnh nguyên vật liệu
            $imagePath = $material->material_img;
            if ($request->hasFile('material_img') && $request->file('material_img')->isValid()) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('material_img')->store('images/materials', 'public');
            }

            // Dữ liệu cập nhật
            $data = [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'quantity' => $request->quantity,
                'material_img' => $imagePath,
                'status' => $request->status,
            ];

            // Cập nhật nguyên vật liệu thông qua repository
            return $this->materialRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật nguyên vật liệu: ' . $e->getMessage());
            throw new \Exception('Cập nhật nguyên vật liệu thất bại');
        }
    }
}
