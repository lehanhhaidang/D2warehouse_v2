<?php

namespace App\Http\Controllers;

use App\Events\ManufacturingPlan\ManufacturingPlanBegin;
use App\Events\ManufacturingPlan\ManufacturingPlanConfirmed;
use App\Events\ManufacturingPlan\ManufacturingPlanCreated;
use App\Events\manufacturingPlan\ManufacturingPlanDeleted;
use App\Events\ManufacturingPlan\ManufacturingPlanFinish;
use App\Events\ManufacturingPlan\ManufacturingPlanRejected;
use App\Events\ManufacturingPlan\ManufacturingPlanSent;
use App\Http\Requests\ManufacturingPlan\StoreManufacturingPlanRequest;
use App\Models\ManufacturingPlan;
use App\Services\ManufacturingPlanService;
use Illuminate\Http\Request;

class ManufacturingPlanController extends Controller
{
    protected $manufacturingPlanService;

    public function __construct(ManufacturingPlanService $manufacturingPlanService)
    {
        $this->manufacturingPlanService = $manufacturingPlanService;
    }
    public function index()
    {
        try {
            $manufacturingPlans = $this->manufacturingPlanService->getAllManufacturingPlanWithDetails();

            return response()->json([
                'data' => $manufacturingPlans,
                'status' => '200'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ], 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManufacturingPlanRequest $request)
    {
        try {
            $manufacturingPlan = $this->manufacturingPlanService->createManufacturingPlanWithDetails($request->all());

            event(new ManufacturingPlanCreated($manufacturingPlan));

            return response()->json([
                'message' => 'Tạo kế hoạch sản xuất thành công',
                'status' => '200'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 200,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $manufacturingPlan = $this->manufacturingPlanService->getManufacturingPlanById($id);

            return response()->json([
                'data' => $manufacturingPlan,
                'status' => '200'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $manufacturingPlan = $this->manufacturingPlanService->updateManufacturingPlanWithDetails($id, $request->all());

            return response()->json([
                'message' => 'Cập nhật kế hoạch sản xuất thành công',
                'data' => $manufacturingPlan,
                'status' => '200'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 200,
            ], $e->getCode() ?: 500);
        }
    }


    public function destroy($id)
    {
        try {
            $this->manufacturingPlanService->deleteManufacturingPlan($id);

            event(new ManufacturingPlanDeleted($id));

            return response()->json([
                'message' => 'Xóa kế hoạch sản xuất thành công',
                'status' => '200'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 200,
            ], $e->getCode() ?: 500);
        }
    }

    public function sendManufacturingPlan($id)
    {
        try {
            $inventoryReport = $this->manufacturingPlanService->sendManufacturingPlan($id);

            event(new ManufacturingPlanSent($id));

            return response()->json([
                'message' => 'Gửi kế hoạch sản xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    public function confirmManufacturingPlan($id)
    {
        try {
            $inventoryReport = $this->manufacturingPlanService->confirmManufacturingPlan($id);

            event(new ManufacturingPlanConfirmed($id));

            return response()->json([
                'message' => 'Duyệt kế hoạch sản xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    public function rejectManufacturingPlan($id)
    {
        try {
            $inventoryReport = $this->manufacturingPlanService->rejectManufacturingPlan($id);

            event(new ManufacturingPlanRejected($id));

            return response()->json([
                'message' => 'Từ chối kế hoạch sản xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ],  500);
        }
    }

    public function beginManufacturing($id)
    {
        try {
            $manufacturingPlan = $this->manufacturingPlanService->beginManufacturing($id);

            event(new ManufacturingPlanBegin($id));

            return response()->json([
                'message' => 'Bắt đầu sản xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
            ],  500);
        }
    }

    public function finishManufacturing($id)
    {
        try {
            $manufacturingPlan = $this->manufacturingPlanService->finishManufacturing($id);

            event(new ManufacturingPlanFinish($id));

            return response()->json([
                'message' => 'Hoàn thành sản xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
            ],  500);
        }
    }
}
