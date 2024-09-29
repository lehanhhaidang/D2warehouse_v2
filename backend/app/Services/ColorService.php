<?php

namespace App\Services;

use App\Repositories\Interface\ColorRepositoryInterface;

class ColorService
{

    protected $colorRepository;

    public function __construct(ColorRepositoryInterface $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }
}
