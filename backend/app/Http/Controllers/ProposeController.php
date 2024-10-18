<?php

namespace App\Http\Controllers;

use App\Http\Requests\Propose\StoreProposeRequest;
use App\Models\Propose;
use Illuminate\Http\Request;
use App\Services\ProposeService;

class ProposeController extends Controller
{
    protected $proposeService;

    public function __construct(ProposeService $proposeService)
    {
        $this->proposeService = $proposeService;
    }
    public function index()
    {
        try {
            $proposes = $this->proposeService->getAllProposeWithDetails();

            return response()->json($proposes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    public function store(StoreProposeRequest $request)
    {
        try {
            $propose = $this->proposeService->createProposeWithDetails($request->all());

            return response()->json([
                'message' => 'Tạo đề xuất thành công',
                'data' => $propose,
                'status' => 201,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi tạo đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    public function show($id)
    {
        try {
            $propose = $this->proposeService->getProposeWithDetails($id);

            return response()->json($propose, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    public function update(StoreProposeRequest $request, $id)
    {
        try {
            $propose = $this->proposeService->updateProposeWithDetails($id, $request->all());

            return response()->json([
                'message' => 'Cập nhật đề xuất thành công',
                'data' => $propose,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $this->proposeService->deleteProposeWithDetails($id);

            return response()->json([
                'message' => 'Xóa đề xuất thành công',
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xóa đề xuất',
                'error' => $e->getMessage(),
                'status' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }
}
