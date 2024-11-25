<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::with('role', 'warehouses')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'img_url' => $user->img_url,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'role_id' => $user->role_id,
                'role_name' => $user->role->name, // Chỉ lấy tên vai trò
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'warehouses' => $user->warehouses->map(function ($warehouse) {
                    return [
                        'id' => $warehouse->id,
                        'name' => $warehouse->name,
                    ];
                }), // Thêm thông tin kho hàng
            ];
        });
    }




    public function find($id)
    {
        $user = User::with('role', 'warehouses')->where('id', $id)->first();

        if ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'img_url' => $user->img_url,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'role_id' => $user->role_id,
                'role_name' => $user->role->name, // Chỉ lấy tên vai trò
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'warehouses' => $user->warehouses->map(function ($warehouse) {
                    return [
                        'id' => $warehouse->id,
                        'name' => $warehouse->name,
                        'assigned_at' => $warehouse->pivot->assigned_at,
                    ];
                }), // Thêm thông tin kho hàng
            ];
        }

        return null;
    }



    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }


    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    public function getEmployeeByWarehouse($warehouseId)
    {
        return User::where('role_id', 4) // Chỉ lấy người dùng có role_id = 4
            ->whereHas('warehouses', function ($query) use ($warehouseId) {
                $query->where('warehouses.id', $warehouseId); // Kiểm tra nhân viên có trong kho
            })
            ->select('id', 'name')
            ->get();
    }
}
