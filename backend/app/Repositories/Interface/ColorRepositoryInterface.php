<?php


namespace App\Repositories\Interface;

use App\Models\Color;


interface ColorRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
