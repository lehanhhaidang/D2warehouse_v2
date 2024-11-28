<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

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


    public function sendResetLink(string $email)
    {
        // Kiểm tra xem email có tồn tại hay không
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new Exception('Email bạn vừa nhập không tồn tại', 404);
        }
        $token = Str::random(60);
        $insertSuccess = DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );
        if (!$insertSuccess) {
            throw new Exception('Lỗi khi lưu token vào cơ sở dữ liệu', 500);
        }
        $resetUrl = env("VITE_BASE_URL") . '/reset-password?token=' . $token . '&email=' . urlencode($email);
        try {
            Mail::to($email)->send(new ResetPasswordMail($token, $resetUrl));
        } catch (Exception $e) {
            throw new Exception('Lỗi khi gửi email reset mật khẩu', 500);
        }

        return true;
    }
}
