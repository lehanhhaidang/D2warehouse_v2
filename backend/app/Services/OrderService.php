<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Interface\OrderRepositoryInterface;

class OrderService
{

    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAll()
    {
        try {
            $oders = $this->orderRepository->getAllOrderWithDetails();

            if ($oders->isEmpty()) {
                throw new \Exception('Hiện tại chưa có đơn hàng nào', 404);
            }

            return $oders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->name,
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'customer_address' => $order->customer_address,
                    'order_date' => $order->order_date,
                    'delivery_date' => $order->delivery_date,
                    'status' => $order->status,
                    'note' => $order->note,
                    'total_price' => $order->total_price,
                    'details' => $order->orderDetails->map(function ($detail) {
                        return [
                            'order_id' => $detail->order_id,
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product->name,
                            'unit' => $detail->product->unit,
                            'quantity' => $detail->quantity,
                            'price' => $detail->price,
                            'total_price' => $detail->total_price,
                        ];
                    }),
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function getOrderById($id)
    {
        try {
            $order = $this->orderRepository->getOrderWithDetailsById($id);

            if (!$order) {
                throw new \Exception('Không tìm thấy đơn hàng', 404);
            }

            return [
                'id' => $order->id,
                'name' => $order->name,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'customer_address' => $order->customer_address,
                'order_date' => $order->order_date,
                'delivery_date' => $order->delivery_date,
                'status' => $order->status,
                'note' => $order->note,
                'total_price' => $order->total_price,
                'details' => $order->orderDetails->map(function ($detail) {
                    return [
                        'order_id' => $detail->order_id,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name,
                        'unit' => $detail->product->unit,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'total_price' => $detail->total_price,
                    ];
                }),
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function confirmOrder($id)
    {
        try {
            $order = $this->findOrder($id);

            $order = $this->orderRepository->update($id, ['status' => 1]);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function startProcessingOrder($id)
    {
        try {
            $order = $this->findOrder($id);

            $order = $this->orderRepository->update($id, ['status' => 2]);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function completeOrder($id)
    {
        try {
            $order = $this->findOrder($id);

            $order = $this->orderRepository->update($id, ['status' => 3]);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function cancelOrder($id)
    {
        try {
            $order = $this->findOrder($id);

            $order = $this->orderRepository->update($id, ['status' => 4]);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    private function findOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            throw new \Exception('Không tìm thấy đơn hàng', 404);
        }

        return $order;
    }

    public function updateStatusOrder(int $orderId, array $data)
    {
        $data = [
            'status' => $data['status'],
        ];

        return $this->orderRepository->update($orderId, $data);
    }
}
