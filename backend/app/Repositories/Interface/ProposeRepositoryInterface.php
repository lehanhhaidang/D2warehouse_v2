<?php

namespace App\Repositories\Interface;

interface ProposeRepositoryInterface
{
    public function getAllProposeWithDetails();

    public function getProposeWithDetails($id);

    public function createPropose(array $data);

    public function createProposeDetail(array $detail);
}
