<?php

namespace App\Repositories\Interface;

use App\Models\Role;

interface RoleRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
