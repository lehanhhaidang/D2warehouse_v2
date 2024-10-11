<?php

namespace App\Services;

use App\Repositories\Interface\ShelfRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Shelf;

class ShelfService
{

    protected $ShelfRepository;

    public function __construct(ShelfRepositoryInterface $ShelfRepository)
    {
        $this->ShelfRepository = $ShelfRepository;
    }

    public function getAllShelf()
    {
        $shelves = $this->ShelfRepository->all();
        if ($shelves->isEmpty()) {
            throw new \Exception('Hiện tại không có kệ hàng nào.', 404);
        }
        return $shelves;
    }

    public function findAShelf($id)
    {
        try {
            $shelf = $this->ShelfRepository->find($id);
            if (!$shelf) {
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.', 404);
            }

            return $shelf;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy kệ hàng với ID: ' . $id);
        } catch (\Exception $e) {
            throw new \Exception('Lỗi khi lấy thông tin kệ hàng: ' . $e->getMessage());
        }
    }





    public function storeShelf($request)
    {
        try {
            $data = [
                'name' => $request->name,
                'warehouse_id' => $request->warehouse_id,
                'number_of_levels' => $request->number_of_levels,
                'storage_capacity' => $request->storage_capacity,
                'category_id' => $request->category_id,
            ];

            $shelf = $this->ShelfRepository->create($data);

            if (!$shelf) {
                throw new \Exception('Tạo kệ hàng thất bại.');
            }
            return $shelf;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function updateShelf($request, $id)
    {
        try {
            // Tìm kệ hàng dựa trên id
            $shelf = $this->ShelfRepository->find($id);
            if (!$shelf) {
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.', 404);
            }

            // Cập nhật dữ liệu
            $data = [
                'name' => $request->name,
                'number_of_levels' => $request->number_of_levels,
                'storage_capacity' => $request->storage_capacity,
            ];

            // Cập nhật kệ hàng
            $shelf->update($data);

            return $shelf;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy kệ hàng với ID: ' . $id, 404);
        } catch (\Exception $e) {
            throw new \Exception('Cập nhật kệ hàng thất bại: ' . $e->getMessage());
        }
    }


    public function deleteShelf($id)
    {
        try {
            // Tìm kệ hàng dựa trên id
            $shelf = $this->ShelfRepository->find($id);
            if (!$shelf) {
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.', 404);
            }
            // Xóa kệ hàng
            return $this->ShelfRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception('Xóa kệ hàng thất bại: ' . $e->getMessage());
        }
    }
}
