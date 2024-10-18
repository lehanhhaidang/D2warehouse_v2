<?php

namespace App\Repositories;

use App\Models\Propose;
use App\Models\ProposeDetail;
use App\Repositories\Interface\ProposeRepositoryInterface;

class ProposeRepository implements ProposeRepositoryInterface
{
    public function getAllProposeWithDetails()
    {
        return Propose::with('details.product', 'details.material', 'warehouse', 'user')->get();
    }

    public function getProposeWithDetails($id)
    {
        return Propose::with('details.product', 'details.material', 'warehouse', 'user')->where('id', $id)->first();
    }

    public function createPropose(array $data)
    {
        return Propose::create($data);
    }

    public function createProposeDetail(array $detail)
    {
        return ProposeDetail::create($detail);
    }


    public function deleteProposeDetailsByProposeId(int $proposeId)
    {
        ProposeDetail::where('propose_id', $proposeId)->delete();
    }

    public function updatePropose(int $id, array $data)
    {
        $propose = Propose::find($id);

        if ($propose) {
            $propose->update($data);
            return $propose;
        }

        return null;
    }

    // Cập nhật propose detail theo id
    public function updateProposeDetail(int $id, array $data)
    {
        $proposeDetail = ProposeDetail::find($id);

        if ($proposeDetail) {
            $proposeDetail->update($data);
            return $proposeDetail;
        }

        return null;
    }

    public function deletePropose($id)
    {
        $propose = Propose::find($id);

        if ($propose) {
            $propose->delete();
            return true;
        }

        return false;
    }
}
