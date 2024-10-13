<?php

namespace App\Repositories;

use App\Models\Propose;
use App\Models\ProposeDetail;
use App\Repositories\Interface\ProposeRepositoryInterface;

class ProposeRepository implements ProposeRepositoryInterface
{
    public function getAllProposeWithDetails()
    {
        return Propose::with('details')->get();
    }

    public function getProposeWithDetails($id)
    {
        return Propose::with('details')->find($id);
    }

    public function createPropose(array $data)
    {
        return Propose::create($data);
    }

    public function createProposeDetail(array $detail)
    {
        return ProposeDetail::create($detail);
    }
}
