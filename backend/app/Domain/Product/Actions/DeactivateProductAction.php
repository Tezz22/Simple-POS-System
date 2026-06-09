<?php

namespace App\Domain\Product\Actions;

use App\Repository\Contracts\ProductRepositoryInterface;

class DeactivateProductAction
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        return $this->repository->deactivate($id);
    }
}