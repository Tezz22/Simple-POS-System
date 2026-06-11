<?php

namespace App\Repository\Eloquent;

use App\Models\Transaction;
use App\Repository\Contracts\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function all()
    {
        return Transaction::latest()->get();
    }

    public function find($id)
    {
        return Transaction::findOrFail($id);
    }

    public function findByInvoice($invoice)
    {
        return Transaction::where('invoice', $invoice)->firstOrFail();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        $transaction = $this->find($id);
        $transaction->update($data);
        return $transaction;
    }

    public function cancel($id)
    {
        $transaction = $this->find($id);
        $transaction->update(['status' => 'cancelled']);
        return $transaction;
    }

    public function pay($id)
    {
        $transaction = $this->find($id);
        $transaction->update(['status' => 'paid']);
        return $transaction;
    }

    public function byDateRange($startDate, $endDate)
    {
        return Transaction::whereBetween('created_at', [$startDate, $endDate])->get();
    }

    public function byCashier($cashierId)
    {
        return Transaction::where('cashier_id', $cashierId)->get();
    }
}