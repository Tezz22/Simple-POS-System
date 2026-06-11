<?php

namespace App\Domain\Product\Actions;

use App\Repository\Contracts\ProductRepositoryInterface;

class DeactivateProductAction
{
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {}

    public function execute($id)
    {
        return $this->repository->deactivate($id);
    }
}