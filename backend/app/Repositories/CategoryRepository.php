<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interface\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.type',
            'categories.parent_id',
        )
            ->whereNotNull('parent_id') // Lọc các bản ghi có parent_id không null
            ->get();
    }

    public function allParentCategory()
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.type',
            'categories.parent_id',
        )
            ->whereNull('parent_id') // Lọc các bản ghi có parent_id null
            ->get();
    }

    public function allProductCategory()
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.type',
            'categories.parent_id',
        )
            ->where('type', 'product')
            ->get();
    }

    public function allMaterialCategory()
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.type',
            'categories.parent_id',
        )
            ->where('type', 'material')
            ->get();
    }


    public function find($id)
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.type',
            'categories.parent_id',
        )->whereNotNull('parent_id')
            ->where('categories.id', $id)
            ->first();
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id,  $data)
    {
        $category = Category::find($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return $category;
        }
        return null;
    }
}
