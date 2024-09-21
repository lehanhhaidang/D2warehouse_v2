<?php

namespace App\Repositories;

use App\Repositories\Interface\RoleRepositoryInterface;
use App\Models\Role;


class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::select(
            'roles.id',
            'roles.name',

        )->get();
    }

    public function find($id)
    {
        return Role::find($id);
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update($id, array $data)
    {
        $role = Role::find($id);
        if ($role) {
            $role->update($data);
            return $role;
        }
        return null;
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return $role;
        }
        return null;
    }
}
