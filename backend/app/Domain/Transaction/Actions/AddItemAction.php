<?php

namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionItemRepositoryInterface;

class AddItemAction
{
    public function __construct(
        protected TransactionItemRepositoryInterface $repository
    ) {}

    public function execute(array $data)
    {
        return $this->repository->create($data);
    }
}