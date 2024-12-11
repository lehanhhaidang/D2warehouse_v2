<?php

namespace App\Services;

use App\Models\ManufacturingPlan;
use App\Models\User;
use App\Repositories\Interface\ManufacturingPlanRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ManufacturingPlanService
{

    protected $manufacturingPlanRepository;

    public function __construct(ManufacturingPlanRepositoryInterface $manufacturingPlanRepository)
    {
        $this->manufacturingPlanRepository = $manufacturingPlanRepository;
    }
    public function getAllManufacturingPlanWithDetails()
    {
        try {
            $manufacturingPlans = $this->manufacturingPlanRepository->getAllManufacturingPlanWithDetails();

            if ($manufacturingPlans->isEmpty()) {
                throw new \Exception('Không tìm thấy kế hoạch sản xuất nào', 404);
            }

            return $manufacturingPlans->map(function ($manufacturingPlans) {
                return [
                    'id' => $manufacturingPlans->id,
                    'name' => $manufacturingPlans->name,
                    'description' => $manufacturingPlans->description,
                    'created_by' => $manufacturingPlans->created_by,
                    'created_by_name' => $manufacturingPlans->user->name,
                    'start_date' => $manufacturingPlans->start_date ?: null,
                    'end_date' => $manufacturingPlans->end_date ?: null,
                    'status' => $manufacturingPlans->status,
                    'begin_manufacturing_by' => $manufacturingPlans->begin_manufacturing_by ?: null,
                    'begin_manufacturing_by_name' => optional(User::find($manufacturingPlans->begin_manufacturing_by))->name ?: null,
                    'finish_manufacturing_by' => $manufacturingPlans->finish_manufacturing_by ?: null,
                    'finish_manufacturing_by_name' => optional(User::find($manufacturingPlans->finish_manufacturing_by))->name ?: null,
                    'manufacturing_plan_details' => $manufacturingPlans->manufacturingPlanDetails->map(function ($manufacturingPlanDetails) {
                        return [
                            'id' => $manufacturingPlanDetails->id,
                            'manufacturing_plan_id' => $manufacturingPlanDetails->manufacturing_plan_id,
                            'product_id' => $manufacturingPlanDetails->product_id,
                            'product_name' => $manufacturingPlanDetails->product->name,
                            'product_unit' => $manufacturingPlanDetails->product->unit,
                            'product_quantity' => $manufacturingPlanDetails->product_quantity,
                            'material_id' => $manufacturingPlanDetails->material_id,
                            'material_name' => $manufacturingPlanDetails->material->name,
                            'material_unit' => $manufacturingPlanDetails->material->unit,
                            'material_quantity' => $manufacturingPlanDetails->material_quantity,
                        ];
                    }),
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getManufacturingPlanById($id)
    {
        try {
            $manufacturingPlans = $this->manufacturingPlanRepository->getManufacturingPlanById($id);

            if (!$manufacturingPlans) {
                throw new \Exception('Không tìm thấy kế hoạch sản xuất', 404);
            }
            return [
                'id' => $manufacturingPlans->id,
                'name' => $manufacturingPlans->name,
                'description' => $manufacturingPlans->description,
                'created_by' => $manufacturingPlans->created_by,
                'created_by_name' => $manufacturingPlans->user->name,
                'start_date' => $manufacturingPlans->start_date ?: null,
                'end_date' => $manufacturingPlans->end_date ?: null,
                'status' => $manufacturingPlans->status,
                'begin_manufacturing_by' => $manufacturingPlans->begin_manufacturing_by ?: null,
                'begin_manufacturing_by_name' => optional(User::find($manufacturingPlans->begin_manufacturing_by))->name ?: null,
                'finish_manufacturing_by' => $manufacturingPlans->finish_manufacturing_by ?: null,
                'finish_manufacturing_by_name' => optional(User::find($manufacturingPlans->finish_manufacturing_by))->name ?: null,
                'manufacturing_plan_details' => $manufacturingPlans->manufacturingPlanDetails->map(function ($manufacturingPlanDetails) {
                    return [
                        'id' => $manufacturingPlanDetails->id,
                        'manufacturing_plan_id' => $manufacturingPlanDetails->manufacturing_plan_id,
                        'product_id' => $manufacturingPlanDetails->product_id,
                        'product_name' => $manufacturingPlanDetails->product->name,
                        'product_unit' => $manufacturingPlanDetails->product->unit,
                        'product_quantity' => $manufacturingPlanDetails->product_quantity,
                        'material_id' => $manufacturingPlanDetails->material_id,
                        'material_name' => $manufacturingPlanDetails->material->name,
                        'material_unit' => $manufacturingPlanDetails->material->unit,
                        'material_quantity' => $manufacturingPlanDetails->material_quantity,
                    ];
                }),
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function createManufacturingPlan(array $data)
    {
        try {
            $manufacturingPlanData = [
                'name' => $data['name'],
                'description' => $data['description'],
                'created_by' => Auth::id(),
            ];

            return $this->manufacturingPlanRepository->createManufacturingPlan($manufacturingPlanData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function createManufacturingPlanDetails(int $manufacturingPlanId, array $detail)
    {

        try {
            $manufacturingPlanDetailData = [
                'manufacturing_plan_id' => $manufacturingPlanId,
                'product_id' => $detail['product_id'],
                'product_quantity' => $detail['product_quantity'],
                'material_id' => $detail['material_id'],
                'material_quantity' => $detail['material_quantity'],
            ];

            return $this->manufacturingPlanRepository->createManufacturingPlanDetail($manufacturingPlanDetailData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function createManufacturingPlanWithDetails(array $data)
    {
        try {
            DB::beginTransaction();
            $manufacturingPlan = $this->createManufacturingPlan($data);

            foreach ($data['manufacturing_plan_details'] as $detail) {
                $this->createManufacturingPlanDetails($manufacturingPlan->id, $detail);
            }
            DB::commit();

            return $manufacturingPlan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), 500);
        }
    }


    public function updateManufacturingPlan(int $manufacturingPlanId, array $data)
    {
        $data = [
            'name' => $data['name'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ];

        return $this->manufacturingPlanRepository->updateManufacturingPlan($manufacturingPlanId, $data);
    }
    public function updateStatusManufacturingPlan(int $manufacturingPlanId, array $data)
    {
        $data = [
            'status' => $data['status'],
        ];

        return $this->manufacturingPlanRepository->updateManufacturingPlan($manufacturingPlanId, $data);
    }

    public function updateManufacturingPlanWithDetails(int $manufacturingPlanId, array $data)
    {
        DB::beginTransaction();
        try {
            $manufacturingPlan = $this->manufacturingPlanRepository->getManufacturingPlanById($manufacturingPlanId);

            if (!$manufacturingPlan) {
                throw new \Exception("Không tìm thấy kế hoạch sản xuất này", 404);
            }

            if ($manufacturingPlan->created_by !== Auth::id()) {
                throw new \Exception("Bạn không có quyền cập nhật kế hoạch sản xuất này", 403);
            }
            $this->updateManufacturingPlan($manufacturingPlanId, $data);

            $this->manufacturingPlanRepository->deleteManufacturingPlanDetailsByManufacturingPlanId($manufacturingPlanId);

            foreach ($data['manufacturing_plan_details'] as $detail) {
                $this->createManufacturingPlanDetails($manufacturingPlanId, $detail);
            }

            DB::commit();

            return $this->getManufacturingPlanById($manufacturingPlanId);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function deleteManufacturingPlan($id)
    {
        try {
            $ManufacturingPlan = ManufacturingPlan::find($id);

            if (!$ManufacturingPlan) {
                throw new \Exception("Không tìm thấy kế hoạch sản xuất này", 404);
            }

            if ($ManufacturingPlan->status !== 0) {
                throw new \Exception("Không thể xóa kế hoạch sản xuất đã được gửi đi hoặc đã được xử lý", 400);
            }

            if ($ManufacturingPlan->created_by !== Auth::id()) {
                throw new \Exception("Bạn không có quyền xóa kế hoạch sản xuất này", 403);
            }

            return $this->manufacturingPlanRepository->deleteManufacturingPlan($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function sendManufacturingPlan($id)
    {
        try {
            $manufacturingPlan = ManufacturingPlan::find($id);

            if (!$manufacturingPlan) {
                throw new \Exception('Không tìm thấy kế hoạch sản xuất này', 404);
            }
            if ($manufacturingPlan->status !== 0) {
                throw new \Exception('Trạng thái kế hoạch sản xuất không hợp lệ, có vẻ phiếu đã được gửi đi từ trước', 400);
            }
            if ($manufacturingPlan->created_by !== Auth::id()) {
                throw new \Exception('Bạn không có quyền gửi kế hoạch này', 403);
            }
            $ManufacturingPlan = $this->manufacturingPlanRepository->updateManufacturingPlan($id, ['status' => 1]);
            return $manufacturingPlan;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function confirmManufacturingPlan($id)
    {
        try {
            $manufacturingPlan = ManufacturingPlan::find($id);
            $roleId = Auth::user()->role_id;

            if (!$manufacturingPlan) {
                throw new \Exception('Không tìm thấy kế hoạch sản xuất', 404);
            }
            if ($manufacturingPlan->status > 1) {
                throw new \Exception('Trạng thái kế hoạch sản xuất không hợp lệ, có vẻ phiếu đã được xử lý', 400);
            }
            if (!in_array($roleId, [3])) {
                throw new \Exception('Bạn không có quyền xử lý kế hoạch sản xuất', 403);
            }

            if ($manufacturingPlan->status < 1) {
                throw new \Exception('Không thể từ chối kế hoạch sản xuất chưa được gửi', 400);
            }
            $manufacturingPlan = $this->manufacturingPlanRepository->updateManufacturingPlan($id, ['status' => 2]);
            return $manufacturingPlan;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    public function rejectManufacturingPlan($id)
    {
        try {
            $manufacturingPlan = ManufacturingPlan::find($id);
            $roleId = Auth::user()->role_id;

            if (!$manufacturingPlan) {
                throw new \Exception('Không tìm thấy kế hoạch sản xuất', 404);
            }
            if ($manufacturingPlan->status > 1) {
                throw new \Exception('Trạng thái kế hoạch sản xuất không hợp lệ, có vẻ phiếu đã được xử lý', 400);
            }
            if (!in_array($roleId, [3])) {
                throw new \Exception('Bạn không có quyền xử lý kế hoạch sản xuất', 403);
            }

            if ($manufacturingPlan->status < 1) {
                throw new \Exception('Không thể từ chối kế hoạch sản xuất chưa được gửi', 400);
            }
            $manufacturingPlan = $this->manufacturingPlanRepository->updateManufacturingPlan($id, ['status' => 8]);
            return $manufacturingPlan;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function beginManufacturing($id)
    {
        $roleId = Auth::user()->role_id;
        if (!in_array($roleId, [2, 4])) {
            throw new \Exception('Bạn không có quyền xử lý kế hoạch sản xuất', 403);
        }
        return $this->manufacturingPlanRepository->updateManufacturingPlan(
            $id,
            [
                'status' => 5,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'begin_manufacturing_by' => Auth::id(),

            ]
        );
    }

    public function finishManufacturing($id)
    {
        $roleId = Auth::user()->role_id;
        if (!in_array($roleId, [2, 4])) {
            throw new \Exception('Bạn không có quyền xử lý kế hoạch sản xuất', 403);
        }
        return $this->manufacturingPlanRepository->updateManufacturingPlan($id, [
            'status' => 6,
            'end_date' => Carbon::now()->format('Y-m-d'),
            'finish_manufacturing_by' => Auth::id(),
        ]);
    }
}
