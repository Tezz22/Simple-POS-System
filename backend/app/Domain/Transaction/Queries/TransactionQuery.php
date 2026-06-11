<?php

namespace App\Domain\Transaction\Queries;

use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionQuery
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository
    ) {}

    public function getAll()
    {
        return $this->transactionRepository->all();
    }

    public function getById(string $id)
    {
        return $this->transactionRepository->find($id);
    }

    public function getByInvoice(string $invoice)
    {
        return $this->transactionRepository
            ->findByInvoice($invoice);
    }

    public function getByCashier(string $cashierId)
    {
        return $this->transactionRepository
            ->byCashier($cashierId);
    }

    public function getTodaySummary()
    {
        $transactions = $this->transactionRepository
            ->byDateRange(
                now()->startOfDay(),
                now()->endOfDay()
            );

        return [
            'total_transaction' => $transactions->count(),
            'total_sales' => $transactions->sum('total'),
        ];
    }
}