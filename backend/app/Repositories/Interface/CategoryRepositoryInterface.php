<?php

namespace App\Repositories\Interface;

interface CategoryRepositoryInterface
{
    public function all();

    public function allParentCategory();

    public function allProductCategory();

    public function allMaterialCategory();

    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function find($id);
}
