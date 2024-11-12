<?php

namespace App\Services;

use App\Models\Propose;
use App\Models\ProposeDetail;
use App\Repositories\Interface\ProposeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProposeService
{
    protected $proposeRepository;

    public function __construct(ProposeRepositoryInterface $proposeRepository)
    {
        $this->proposeRepository = $proposeRepository;
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
                    'warehouse_name' => $propose->warehouse ? $propose->warehouse->name : null,
                    'status' => $propose->status,
                    'description' => $propose->description,
                    'created_by' => $propose->user ? $propose->user->name : null,
                    'created_at' => $propose->created_at,
                    'updated_at' => $propose->updated_at,
                    'details' => $propose->details->map(function ($detail) {
                        return [
                            'propose_id' => $detail->propose_id,
                            'product_name' => $detail->product->name ?? null,
                            'material_name' => $detail->material->name ?? null,
                            'unit' => $detail->unit,
                            'quantity' => $detail->quantity,
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
                'warehouse_name' => $propose->warehouse ? $propose->warehouse->name : null,
                'status' => $propose->status,
                'description' => $propose->description,
                'created_by' => $propose->user ? $propose->user->name : null,
                'created_at' => $propose->created_at,
                'updated_at' => $propose->updated_at,
                'details' => $propose->details->map(function ($detail) {
                    return [
                        'propose_id' => $detail->propose_id,
                        'product_name' => $detail->product->name ?? null,
                        'material_name' => $detail->material->name ?? null,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
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
        ];

        return $this->proposeRepository->createPropose($proposeData);
    }


    protected function createProposeDetails(int $proposeId, array $detail)
    {
        $proposeDetailData = [
            'propose_id' => $proposeId,
            'unit' => $detail['unit'],
            'quantity' => $detail['quantity'],
        ];

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

            if (!$propose) {
                throw new \Exception('Không tìm thấy đề xuất', 404);
            }
            if ($propose->created_by !== Auth::id()) {
                throw new \Exception("Bạn không có quyền gửi đề xuất này", 403);
            }
            $propose = $this->proposeRepository->updatePropose($id, ['status' => 1]);
            return $propose;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function acceptPropse($id)
    {
        try {
            $propose = Propose::find($id);

            if (!$propose) {
                throw new \Exception('Không tìm thấy đề xuất', 404);
            }
            $propose = $this->proposeRepository->updatePropose($id, ['status' => 2]);
            return $propose;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function rejectPropose($id)
    {
        try {
            $propose = Propose::find($id);

            if (!$propose) {
                throw new \Exception('Không tìm thấy đề xuất', 404);
            }
            $propose = $this->proposeRepository->updatePropose($id, ['status' => 3]);
            return $propose;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
