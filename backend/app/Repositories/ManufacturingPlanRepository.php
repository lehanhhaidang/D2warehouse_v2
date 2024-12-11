<?php

namespace App\Repositories;

use App\Models\ManufacturingPlan;
use App\Models\ManufacturingPlanDetail;
use App\Repositories\Interface\ManufacturingPlanRepositoryInterface;

class ManufacturingPlanRepository implements ManufacturingPlanRepositoryInterface
{
    public function getAllManufacturingPlanWithDetails()
    {
        return ManufacturingPlan::with('manufacturingPlanDetails')->get();
    }

    public function getManufacturingPlanById($id)
    {
        return ManufacturingPlan::with('manufacturingPlanDetails')->find($id);
    }

    public function createManufacturingPlan($data)
    {
        return ManufacturingPlan::create($data);
    }

    public function createManufacturingPlanDetail($data)
    {
        return ManufacturingPlanDetail::create($data);
    }


    public function updateManufacturingPlan(int $id, array $data)
    {
        $manufacturingnPlan = ManufacturingPlan::find($id);

        if ($manufacturingnPlan) {
            $manufacturingnPlan->update($data);
            return $manufacturingnPlan;
        }

        return null;
    }

    // Cập nhật propose detail theo id
    public function updateManufacturingPlanDetail(int $id, array $data)
    {
        $manufacturingnPlanDetail = ManufacturingPlanDetail::find($id);

        if ($manufacturingnPlanDetail) {
            $manufacturingnPlanDetail->update($data);
            return $manufacturingnPlanDetail;
        }

        return null;
    }

    public function deleteManufacturingPlan($id)
    {
        return ManufacturingPlan::where('id', $id)->delete();
    }

    public function deleteManufacturingPlanDetailsByManufacturingPlanId(int $manufacturingPlanId)
    {
        return ManufacturingPlanDetail::where('manufacturing_plan_id', $manufacturingPlanId)->delete();
    }
}
