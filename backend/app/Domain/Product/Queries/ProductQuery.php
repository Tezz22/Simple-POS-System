<?php

namespace App\Domain\Product\Queries;

use App\Repository\Contracts\ProductRepositoryInterface;

class ProductQuery
{
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {}

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function getLowStock()
    {
        return $this->repository->lowStock();
    }
}