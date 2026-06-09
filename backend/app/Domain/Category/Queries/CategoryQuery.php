<?php

namespace App\Domain\Category\Queries;

use App\Repository\Contracts\CategoryRepositoryInterface;

class CategoryQuery
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }
}
