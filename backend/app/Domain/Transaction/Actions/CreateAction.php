<?php

namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionRepositoryInterface;

class CreateAction
{
    public function __construct(
        protected TransactionRepositoryInterface $repository
    ) {}

    public function execute(array $data)
    {
        $data['status'] = 'pending';

        return $this->repository->create($data);
    }
}