<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function index()
    {
        try {
            $orders = $this->orderService->getAll();
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy thông tin đơn hàng thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lấy thông tin đơn hàng thất bại',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
