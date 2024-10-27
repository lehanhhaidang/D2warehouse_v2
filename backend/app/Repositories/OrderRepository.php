<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interface\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAllOrderWithDetails()
    {
        return Order::with(['orderDetails'])->get();
    }

    public function getOrderWithDetailsById($id)
    {
        return Order::with(['orderDetails'])->find($id);
    }

    public function create(array $attributes)
    {
        return Order::create($attributes);
    }

    public function update($id, array $attributes)
    {
        return Order::find($id)->update($attributes);
    }

    public function delete($id)
    {
        return Order::find($id)->delete();
    }
}
