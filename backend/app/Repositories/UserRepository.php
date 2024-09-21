<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::with('role')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'img_url' => $user->img_url,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'role_name' => $user->role->name, // Chỉ lấy tên vai trò
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });
    }



    public function find($id)
    {
        $user = User::with('role')->where('id', $id)->first();

        if ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'img_url' => $user->img_url,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'role_name' => $user->role->name, // Chỉ lấy tên vai trò
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
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
}
