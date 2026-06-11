<?php

namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionItemRepositoryInterface;

class UpdateItemAction
{
    public function __construct(
        protected TransactionItemRepositoryInterface $repository
    ) {}

    public function execute(string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }
}