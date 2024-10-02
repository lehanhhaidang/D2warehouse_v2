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
            throw new \Exception('Hiện tại không có kệ hàng nào.');
        }
        return $shelves;
    }

    public function findAShelf($id)
    {
        return $this->ShelfRepository->find($id);
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
            return $this->ShelfRepository->create($data);
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
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.');
            }

            // Cập nhật dữ liệu
            $data = [
                'name' => $request->name,
                'warehouse_id' => $request->warehouse_id,
                'number_of_levels' => $request->number_of_levels,
                'storage_capacity' => $request->storage_capacity,
                'category_id' => $request->category_id,
            ];

            // Cập nhật kệ hàng
            $shelf->update($data);

            return $shelf;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy kệ hàng với ID: ' . $id);
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
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.');
            }

            // Xóa kệ hàng
            return $this->ShelfRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy kệ hàng.');
        } catch (\Exception $e) {
            throw new \Exception('Xóa kệ hàng thất bại: ' . $e->getMessage());
        }
    }
}
