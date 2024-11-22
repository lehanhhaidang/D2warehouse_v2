<?php


namespace App\Repositories\Interface;

interface ShelfRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function filterShelves($warehouseId, $ccategoryId);


    public function getShelvesWithProductsByWarehouseId($id);

    public function getShelvesWithMaterialsByWarehouseId($id);
}
