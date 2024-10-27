<?php

namespace App\Repositories\Interface;

interface OrderRepositoryInterface
{
    public function getAllOrderWithDetails();

    public function getOrderWithDetailsById($id);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);
}
