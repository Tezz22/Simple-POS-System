<?php

namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionRepositoryInterface;
use App\Repository\Contracts\TransactionItemRepositoryInterface;

class PrintReceiptAction
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected TransactionItemRepositoryInterface $itemRepository
    ) {}

    public function execute(string $id)
    {
        return [
            'transaction' => $this->transactionRepository->find($id),
            'items' => $this->itemRepository->byTransaction($id),
        ];
    }
}