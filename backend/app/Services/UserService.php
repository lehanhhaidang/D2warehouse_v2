<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store($data)
    {
        try {
            // Tạo user mới
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'img_url' => $data['img_url'] ?? null,
                'phone' => $data['phone'],
                'role_id' => $data['role_id'],
                'created_at' => now(),
            ];
            $user = $this->userRepository->create($userData);

            // Gắn kho cho user nếu có warehouse_ids
            if (isset($data['warehouse_ids'])) {
                foreach ($data['warehouse_ids'] as $warehouseId) {
                    DB::table('warehouse_staff')->insert([
                        'user_id' => $user->id,
                        'warehouse_id' => $warehouseId,
                        'assigned_at' => now(),
                    ]);
                }
            }

            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0, $e);
        }
    }

    public function update($data, $id)
    {
        try {
            // Cập nhật user
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'img_url' => $data['img_url'] ?? null,
                'phone' => $data['phone'],
                'role_id' => $data['role_id'],
            ];

            $user = $this->userRepository->update($id, $userData);

            if (!$user) {
                throw new Exception('Không tìm thấy người dùng này');
            }

            // Cập nhật kho cho user nếu có warehouse_ids
            if (isset($data['warehouse_ids'])) {
                // Xóa bản ghi cũ trong bảng warehouse_staff
                DB::table('warehouse_staff')->where('user_id', $id)->delete();

                // Thêm bản ghi mới
                foreach ($data['warehouse_ids'] as $warehouseId) {
                    DB::table('warehouse_staff')->insert([
                        'user_id' => $id,
                        'warehouse_id' => $warehouseId,
                        'assigned_at' => now(),
                    ]);
                }
            }

            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0, $e);
        }
    }
}
