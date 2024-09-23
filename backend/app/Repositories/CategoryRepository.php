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
        )->get();
    }

    public function find($id)
    {
        return Category::select(
            'categories.id',
            'categories.name',
        )->where('categories.id', $id)->first();
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
