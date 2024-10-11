<?php

namespace App\Services;

use App\Repositories\Interface\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService
{

    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        try {
            $categories = $this->categoryRepository->all();
            if ($categories->isEmpty()) {
                throw new \Exception('Hiện tại không có danh mục nào.');
            }
            return $categories;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function getCategoryById($id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            if (!$category) {
                throw new ModelNotFoundException('Không tìm thấy danh mục.');
            }

            return $category;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy danh mục với ID: ' . $id);
        } catch (\Exception $e) {
            throw new \Exception('Lỗi khi lấy thông tin danh mục: ' . $e->getMessage());
        }
    }


    public function createCategory($request)
    {
        try {
            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'parent_id' => $request->parent_id,
            ];

            $category = $this->categoryRepository->create($data);

            if (!$category) {
                throw new \Exception('Tạo danh mục thất bại.');
            }
            return $category;
        } catch (\Exception $e) {
            throw $e;
        }
    }



    public function updateCategory($request, $id)
    {
        try {

            $category = $this->categoryRepository->find($id);
            if (!$category) {
                throw new ModelNotFoundException('Không tìm thấy danh mục.');
            }

            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'parent_id' => $request->parent_id,
            ];

            $category->update($data);

            if (!$category) {
                throw new \Exception('Cập nhật danh mục thất bại.');
            }
            return $category;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function deleteCategory($id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            if (!$category) {
                throw new ModelNotFoundException('Không tìm thấy danh mục.');
            }

            $category->delete();

            return $category;
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Không tìm thấy danh mục với ID: ' . $id);
        } catch (\Exception $e) {
            throw new \Exception('Lỗi khi xóa danh mục: ' . $e->getMessage());
        }
    }
}
