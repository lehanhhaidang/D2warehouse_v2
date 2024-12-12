<?php

namespace App\Services;

use App\Models\ManufacturingPlan;
use App\Models\Propose;
use App\Models\ProposeDetail;
use App\Models\User;
use App\Repositories\Interface\ProposeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProposeService
{
    protected $proposeRepository;

    protected $manufacturingPlanService;

    public function __construct(
        ProposeRepositoryInterface $proposeRepository,
        ManufacturingPlanService $manufacturingPlanService
    ) {
        $this->proposeRepository = $proposeRepository;
        $this->manufacturingPlanService = $manufacturingPlanService;
    }

    public function getAllProposeWithDetails()
    {
        try {
            $proposes = $this->proposeRepository->getAllProposeWithDetails();

            if ($proposes->isEmpty()) {
                throw new \Exception('Hiện tại chưa có đề xuất nào', 404);
            }

            return $proposes->map(function ($propose) {
                return [
                    'id' => $propose->id,
                    'name' => $propose->name,
                    'type' => $propose->type,
                    'warehouse_id' => $propose->warehouse_id,
                    'warehouse_name' => $propose->warehouse ? $propose->warehouse->name : null,
                    'order_id' => $propose->order_id,
                    'order_name' => $propose->order ? $propose->order->name : null,
                    'status' => $propose->status,
                    'assigned_to' => $propose->assigned_to,
                    'assigned_to_name' => User::find($propose->assigned_to)->name ?? null,
                    'description' => $propose->description,
                    'created_by' => $propose->created_by,
                    'created_by_name' => $propose->user->name ?? null,
                    'manufacturing_plan_id' => $propose->manufacturing_plan_id,
                    'manufacturing_plan_name' => ManufacturingPlan::find($propose->manufacturing_plan_id)->name ?? null,
                    'created_at' => $propose->created_at,
                    'updated_at' => $propose->updated_at,
                    'details' => $propose->details->map(function ($detail) {
                        return [
                            'propose_id' => $detail->propose_id,
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product->name ?? null,
                            'material_id' => $detail->material_id,
                            'material_name' => $detail->material->name ?? null,
                            'unit' => $detail->unit,
                            'quantity' => $detail->quantity,
                            'note' => $detail->note ?? null,
                        ];
                    }),
                ];
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getProposeWithDetails($id)
    {
        try {
            $propose = $this->proposeRepository->getProposeWithDetails($id);

            if (!$propose) {
                throw new \Exception('Không tìm thấy đề xuất', 404);
            }

            return [
                'id' => $propose->id,
                'name' => $propose->name,
                'type' => $propose->type,
                'warehouse_id' => $propose->warehouse_id,
                'warehouse_name' => $propose->warehouse ? $propose->warehouse->name : null,
                'order_id' => $propose->order_id,
                'order_name' => $propose->order ? $propose->order->name : null,
                'status' => $propose->status,
                'assigned_to' => $propose->assigned_to,
                'assigned_to_name' => User::find($propose->assigned_to)->name ?? null,
                'description' => $propose->description,
                'created_by' => $propose->created_by,
                'created_by_name' => $propose->user->name ?? null,
                'manufacturing_plan_id' => $propose->manufacturing_plan_id,
                'manufacturing_plan_name' => ManufacturingPlan::find($propose->manufacturing_plan_id)->name ?? null,
                'created_at' => $propose->created_at,
                'updated_at' => $propose->updated_at,
                'details' => $propose->details->map(function ($detail) {
                    return [
                        'propose_id' => $detail->propose_id,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name ?? null,
                        'material_id' => $detail->material_id,
                        'material_name' => $detail->material->name ?? null,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
                        'note' => $detail->note ?? null,
                    ];
                }),
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    protected function createPropose(array $data)
    {
        $proposeData = [
            'name' => $data['name'],
            'warehouse_id' => $data['warehouse_id'],
            'status' => $data['status'],
            'description' => $data['description'],
            'type' => $data['type'],
            'created_by' => Auth::id(),
            'assigned_to' => $data['assigned_to'] ?? null,
            'order_id' => $data['order_id'] ?? null,
            'manufacturing_plan_id' => $data['manufacturing_plan_id'] ?? null,
        ];

        return $this->proposeRepository->createPropose($proposeData);
    }


    protected function createProposeDetails(int $proposeId, array $detail)
    {
        $proposeDetailData = [
            'propose_id' => $proposeId,
            'unit' => $detail['unit'],
            'quantity' => $detail['quantity'],
            'note' => $detail['note'] ?? null,
        ];

        if (Propose::find($proposeId)->type !== 'DXXNVL') {
            if ($proposeDetailData['quantity'] <= 0 || $proposeDetailData['quantity'] % 100 !== 0) {
                throw new \Exception('Số lượng phải là bội số của 100 ', 400);
            }
        }

        if ($proposeDetailData['quantity'] > 5000) {
            throw new \Exception('Số lượng sản phẩm không được vượt quá 5000', 400);
        }

        // Kiểm tra loại propose để lưu product_id hoặc material_id
        if (isset($detail['product_id'])) {
            $proposeDetailData['product_id'] = $detail['product_id'];
        } elseif (isset($detail['material_id'])) {
            $proposeDetailData['material_id'] = $detail['material_id'];
        }

        return $this->proposeRepository->createProposeDetail($proposeDetailData);
    }


    public function createProposeWithDetails(array $data)
    {
        DB::beginTransaction();
        try {
            // Tạo propose mới thông qua hàm createPropose
            $propose = $this->createPropose($data);

            // Duyệt qua từng chi tiết và tạo mới propose_details
            foreach ($data['details'] as $detail) {
                $this->createProposeDetails($propose->id, $detail);
            }


            DB::commit();
            return $propose;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }




    protected function updatePropose(int $proposeId, array $data)
    {
        $proposeData = [
            'name' => $data['name'],
            'warehouse_id' => $data['warehouse_id'],
            'status' => $data['status'],
            'description' => $data['description'],
            'assigned_to' => $data['assigned_to'],
            'type' => $data['type'],
        ];

        return $this->proposeRepository->updatePropose($proposeId, $proposeData);
    }

    public function updateProposeWithDetails(int $proposeId, array $data)
    {
        DB::beginTransaction();
        try {
            // Lấy đề xuất hiện tại từ repository
            $propose = $this->proposeRepository->getProposeWithDetails($proposeId);

            // Nếu không tìm thấy, báo lỗi
            if (!$propose) {
                throw new \Exception("Không tìm thấy đề xuất", 404);
            }

            // Kiểm tra xem người dùng hiện tại có phải là người tạo ra propose này không
            if ($propose->created_by !== Auth::id()) {
                throw new \Exception("Bạn không có quyền chỉnh sửa đề xuất này", 403);
            }

            if ($propose->status !== 0) {
                throw new \Exception("Không thể chỉnh sửa đề xuất đã được gửi đi", 403);
            }

            // Cập nhật propose thông qua hàm updatePropose
            $this->updatePropose($proposeId, $data);

            // Xóa tất cả các details cũ liên quan đến propose này
            $this->proposeRepository->deleteProposeDetailsByProposeId($proposeId);

            // Duyệt qua từng chi tiết và tạo mới propose_details
            foreach ($data['details'] as $detail) {
                $this->createProposeDetails($proposeId, $detail);
            }

            DB::commit();
            return $this->proposeRepository->getProposeWithDetails($proposeId);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function deleteProposeWithDetails(int $proposeId)
    {
        DB::beginTransaction();
        try {
            // Lấy đề xuất hiện tại từ repository
            $propose = $this->proposeRepository->getProposeWithDetails($proposeId);

            // Nếu không tìm thấy, báo lỗi
            if (!$propose) {
                throw new \Exception("Không tìm thấy đề xuất", 404);
            }

            // Kiểm tra xem người dùng hiện tại có phải là người tạo ra propose này không
            if ($propose->created_by !== Auth::id()) {
                throw new \Exception("Bạn không có quyền xóa đề xuất này", 403);
            }

            if ($propose->status !== 0) {
                throw new \Exception("Không thể xóa đề xuất đã được gửi đi", 403);
            }


            // Xóa propose
            $this->proposeRepository->deletePropose($proposeId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function sendPropose($id)
    {
        try {
            $propose = Propose::find($id);

            abort_if(!$propose, 404, 'Không tìm thấy đề xuất!');
            abort_if($propose->created_by !== Auth::id(), 403, 'Bạn không có quyền gửi đề xuất này!');
            abort_if($propose->status !== 0, 403, 'Trạng thái đề xuất không hợp lệ, có vẻ đề xuất này đã được gửi đi từ trước.');

            return $this->proposeRepository->updatePropose($id, ['status' => 1]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    // public function acceptPropse($id)
    // {
    //     try {
    //         $propose = Propose::find($id);
    //         $roleId = User::find(Auth::id())->role_id;

    //         abort_if(!$propose, 404, 'Không tìm thấy đề xuất!');
    //         abort_if(
    //             ($propose->type === 'DXNTP' || $propose->type === 'DXXTP') && !in_array($roleId, [2, 3]) ||
    //                 ($propose->type === 'DXNVL' || $propose->type === 'DXXNVL') && $roleId !== 3,
    //             403,
    //             'Vai trò của bạn không phù hợp để duyệt đề xuất này!'
    //         );
    //         abort_if($propose->status > 1, 403, 'Trạng thái đề xuất không hợp lệ, có vẻ đề xuất này đã được xử lý.');

    //         return $this->proposeRepository->updatePropose($id, ['status' => 2]);
    //     } catch (\Exception $e) {
    //         throw new \Exception($e->getMessage(), $e->getCode());
    //     }
    // }

    // public function rejectPropose($id)
    // {
    //     try {
    //         $propose = Propose::find($id);
    //         $roleId = User::find(Auth::id())->role_id;

    //         abort_if(!$propose, 404, 'Không tìm thấy đề xuất!');
    //         abort_if(
    //             ($propose->type === 'DXNTP' || $propose->type === 'DXXTP') && !in_array($roleId, [2, 3]) ||
    //                 ($propose->type === 'DXNVL' || $propose->type === 'DXXNVL') && $roleId !== 3,
    //             403,
    //             'Vai trò của bạn không phù hợp để từ chối đề xuất này!'
    //         );
    //         abort_if($propose->status > 1, 403, 'Trạng thái đề xuất không hợp lệ, có vẻ đề xuất này đã được xử lý.');

    //         return $this->proposeRepository->updatePropose($id, ['status' => 3]);
    //     } catch (\Exception $e) {
    //         throw new \Exception($e->getMessage(), $e->getCode());
    //     }
    // }


    public function handlePropose($id, $status)
    {
        try {
            $propose = Propose::find($id);
            $roleId = User::find(Auth::id())->role_id;

            abort_if(!$propose, 404, 'Không tìm thấy đề xuất!');
            abort_if($propose->status < 1, 403, 'Trạng thái đề xuất không hợp lệ, có vẻ đề xuất này chưa được gửi đi.');
            abort_if(
                ($propose->type === 'DXNTP' || $propose->type === 'DXXTP') && !in_array($roleId, [2, 3, 4]) ||
                    ($propose->type === 'DXNNVL' || $propose->type === 'DXXNVL') && $roleId !== 3 && $roleId !== 4,
                403,
                'Vai trò của bạn không phù hợp để xử lý đề xuất này!'
            );
            abort_if($propose->status == 4, 403, 'Trạng thái đề xuất không hợp lệ, có vẻ đề xuất này đã được xử lý.');

            if ($propose->type === 'DXXNVL') {
                $manufacturingPlanId = Propose::find($propose->id)->manufacturing_plan_id;

                $this->manufacturingPlanService->updateStatusManufacturingPlan($manufacturingPlanId, ['status' => 3]);
            }
            return $this->proposeRepository->updatePropose($id, ['status' => $status]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function acceptPropose($id)
    {
        return $this->handlePropose($id, 2);
    }

    public function rejectPropose($id)
    {
        return $this->handlePropose($id, 3);
    }
}
