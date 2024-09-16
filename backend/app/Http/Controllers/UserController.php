<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $results = $this->userRepository->all();
        return $results;
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $this->userRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'message' => 'Tạo mới người dùng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng này',
            ], 404);
        }

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = $this->userRepository->update($id, [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            if (!$user) {
                return response()->json([
                    'message' => 'Không tìm thấy người dùng này',
                ], 404);
            }

            return response()->json([
                'message' => 'Cập nhật người dùng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->userRepository->delete($id);
        if (!$deleted) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng này',
            ], 404);
        }

        return response()->json([
            'message' => 'Xóa người dùng thành công',
        ], 200);
    }
}
