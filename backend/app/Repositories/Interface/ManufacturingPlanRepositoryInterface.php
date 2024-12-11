<?php

namespace App\Repositories\Interface;

use App\Models\ManufacturingPlan;

interface ManufacturingPlanRepositoryInterface
{
    public function getAllManufacturingPlanWithDetails();
    public function getManufacturingPlanById($id);

    public function createManufacturingPlan(array $data);
    public function createManufacturingPlanDetail(array $detail);

    public function updateManufacturingPlan(int $id, array $data);

    public function updateManufacturingPlanDetail(int $id, array $data);

    public function deleteManufacturingPlan($id);

    public function deleteManufacturingPlanDetailsByManufacturingPlanId(int $manufacturingPlanId);
}
