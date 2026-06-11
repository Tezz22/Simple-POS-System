<?php

namespace App\Domain\Transaction\Actions;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\TransactionItemRepositoryInterface;

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