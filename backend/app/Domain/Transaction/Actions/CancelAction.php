<?php

namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionRepositoryInterface;

class CancelAction
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(string $id)
    {
        return $this->transactionRepository->cancel($id);
    }
}