<?php

namespace App\Repositories\Interface;

use App\Models\ProposeDetail;

interface ProposeRepositoryInterface
{
    public function getAllProposeWithDetails();

    public function getProposeWithDetails($id);

    public function createPropose(array $data);

    public function createProposeDetail(array $detail);

    public function deletePropose(int $id);

    public function deleteProposeDetailsByProposeId(int $proposeId);

    public function updatePropose(int $id, array $data);

    public function updateProposeDetail(int $id, array $data);
}
