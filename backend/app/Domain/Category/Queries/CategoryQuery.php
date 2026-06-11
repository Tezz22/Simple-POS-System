<?php

namespace App\Domain\Category\Queries;

use App\Repository\Contracts\CategoryRepositoryInterface;

class CategoryQuery
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {}

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }
}
