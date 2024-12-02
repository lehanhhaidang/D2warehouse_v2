<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Product;
use App\Repositories\Interface\ShelfRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Shelf;
use App\Models\ShelfDetail;
use App\Models\Warehouse;

class ShelfService
{

    protected $shelfRepository;

    public function __construct(ShelfRepositoryInterface $shelfRepository)
    {
        $this->shelfRepository = $shelfRepository;
    }

    public function getAllShelf()
    {
        $shelves = $this->shelfRepository->all();
        if ($shelves->isEmpty()) {
            throw new \Exception('Hiện tại không có kệ hàng nào.', 404);
        }
        return $shelves;
    }

    public function findAShelf($id)
    {
        try {
            $shelf = $this->shelfRepository->find($id);
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

            $shelf = $this->shelfRepository->create($data);

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
            $shelf = $this->shelfRepository->find($id);
            if (!$shelf) {
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.', 404);
            }

            // Cập nhật dữ liệu
            $data = [
                'name' => $request->name,
                'number_of_levels' => $request->number_of_levels,
                'storage_capacity' => $request->storage_capacity,
                'category_id' => $request->category_id,
                'warehouse_id' => $request->warehouse_id,
            ];

            if ($data['warehouse_id'] != $shelf->warehouse_id) {
                $shelfDetails = ShelfDetail::where('shelf_id', $id)->get();
                if ($shelfDetails->count() > 0) {
                    throw new \Exception('Kệ hàng đang chứa hàng, không thể chuyển sang kho hàng khác.');
                }
            }
            if ($data['category_id'] != $shelf->category_id) {
                $shelfDetails = ShelfDetail::where('shelf_id', $id)->get();
                if ($shelfDetails->count() > 0) {
                    throw new \Exception('Kệ hàng đang chứa hàng, không thể chuyển sang danh mục khác.');
                }
            }
            // Cập nhật kệ hàng
            $shelf->update($data);

            return $shelf;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy kệ hàng với ID: ' . $id, 404);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function deleteShelf($id)
    {
        try {
            // Tìm kệ hàng dựa trên id
            $shelf = $this->shelfRepository->find($id);
            if (!$shelf) {
                throw new ModelNotFoundException('Không tìm thấy kệ hàng.', 404);
            }
            if ($shelf->details->count() > 0) {
                throw new \Exception('Kệ hàng đang chứa hàng, không thể xóa.');
            }
            // Xóa kệ hàng
            return $this->shelfRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function filterShelves($warehouseId, $productId = null, $materialId = null)
    {
        $categoryId = null;

        // Kiểm tra product_id để lấy category_id
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $categoryId = $product->category_id;
            }
        }

        // Kiểm tra material_id để lấy category_id
        if ($materialId) {
            $material = Material::find($materialId);
            if ($material) {
                $categoryId = $material->category_id;
            }
        }

        // Nếu không tìm thấy category_id từ cả product và material thì return null
        if (!$categoryId) {
            return [];
        }

        // Gọi repository để lọc kệ dựa trên warehouse_id và category_id
        $shelves = $this->shelfRepository->filterShelves($warehouseId, $categoryId);

        $quantities = [];

        // Tính tổng quantity cho từng shelf_id, bỏ qua product_id và material_id
        foreach ($shelves as $shelf) {
            // Nếu chưa tính tổng quantity cho shelf_id này thì tính
            if (!isset($quantities[$shelf['id']])) {
                $quantities[$shelf['id']] = ShelfDetail::where('shelf_id', $shelf['id'])
                    ->sum('quantity');
            }
        }

        // Cập nhật lại quantity cho tất cả các shelf có cùng shelf_id
        foreach ($shelves as &$shelf) {
            // Lấy tổng quantity của shelf_id hiện tại
            $quantity = $quantities[$shelf['id']] ?? 0;  // Nếu không có thì gán mặc định là 0
            $shelf['name'] .= " ({$quantity})";
        }
        // foreach ($shelves as &$shelf) {
        //     $quantity = ShelfDetail::where('shelf_id', $shelf['id'])
        //         ->when($productId, function ($query) use ($productId) {
        //             $query->where('product_id', $productId);
        //         })
        //         ->when($materialId, function ($query) use ($materialId) {
        //             $query->where('material_id', $materialId);
        //         })
        //         ->sum('quantity');

        //     $shelf['name'] .= " ({$quantity})";
        // }

        return $shelves;
    }

    public function filterShelvesExport($warehouseId, $productId = null, $materialId = null)
    {
        $categoryId = null;

        // Kiểm tra product_id để lấy category_id
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $categoryId = $product->category_id;
            }
        }

        // Kiểm tra material_id để lấy category_id
        if ($materialId) {
            $material = Material::find($materialId);
            if ($material) {
                $categoryId = $material->category_id;
            }
        }

        // Nếu không tìm thấy category_id từ cả product và material thì return null
        if (!$categoryId) {
            return [];
        }

        // Gọi repository để lọc kệ dựa trên warehouse_id và category_id
        $shelves = $this->shelfRepository->filterShelves($warehouseId, $categoryId);

        // Thêm thông tin tổng quantity vào name
        foreach ($shelves as &$shelf) {
            $quantity = ShelfDetail::where('shelf_id', $shelf['id'])
                ->when($productId, function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->when($materialId, function ($query) use ($materialId) {
                    $query->where('material_id', $materialId);
                })
                ->sum('quantity');

            $shelf['name'] .= " ({$quantity})";
        }

        return $shelves;
    }

    public function getShelfItemsByWarehouseId($id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            throw new \Exception('Không tìm thấy kho hàng.', 404);
        }

        if ($warehouse->category_id == 1) {
            $materials = $this->shelfRepository->getShelvesWithMaterialsByWarehouseId($id);
            return $materials;
        } elseif ($warehouse->category_id == 2) {
            $products = $this->shelfRepository->getShelvesWithProductsByWarehouseId($id);
            return $products;
        } else {
            throw new \Exception('Invalid warehouse category', 400);
        }
    }


    public function getShelvesWithDetails()
    {
        try {
            $shelves = $this->shelfRepository->getShelvesWithDetails();
            if ($shelves->isEmpty()) {
                throw new \Exception('Không tìm thấy kệ hàng nào.', 404);
            }
            return $shelves;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getShelfDetailsById($id)
    {
        try {
            $shelf = $this->shelfRepository->getShelfDetailsById($id);
            if (Shelf::find($id) == null) {
                throw new \Exception('Không tìm thấy kệ hàng này.', 404);
            }
            // if ($shelf->isEmpty()) {
            //     throw new \Exception('Kệ hàng không chứa hàng nào.', 404);
            // }
            return $shelf;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function getShelvesWithDetailsByWarehouseId($id)
    {
        try {
            $shelves = $this->shelfRepository->getShelvesWithDetailsByWarehouseId($id);
            if ($shelves->isEmpty()) {
                throw new \Exception('Không tìm thấy kệ hàng nào.', 404);
            }
            return $shelves;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
