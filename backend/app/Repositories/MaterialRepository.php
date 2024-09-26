<?php

namespace App\Repositories;

use App\Models\Material;
use App\Repositories\Interface\MaterialRepositoryInterface;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function all()
    {
        return Material::select(
            'materials.id',
            'materials.name',
            'materials.unit',
            'materials.quantity',
            'materials.material_img',
            'materials.status',
            'materials.created_at',
            'materials.updated_at',

        )->join('categories', 'materials.category_id', '=', 'categories.id')

            ->get();
    }

    public function find($id)
    {
        return Material::select(
            'materials.id',
            'materials.name',
            'materials.unit',
            'materials.quantity',

            'materials.material_img',
            'materials.status',
            'materials.created_at',
            'materials.updated_at',

        )
            ->join('categories', 'materials.category_id', '=', 'categories.id')

            ->where('materials.id', $id)
            ->first();
    }

    public function create(array $data)
    {
        return Material::create($data);
    }

    public function update($id, array $data)
    {
        $material = Material::find($id);
        if ($material) {
            $material->update($data);
            return $material;
        }
        return null;
    }

    public function delete($id)
    {
        $material = Material::find($id);
        if ($material) {
            $material->delete();
            return true;
        }
        return false;
    }
}
