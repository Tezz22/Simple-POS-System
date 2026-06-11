<?php

namespace App\Repository\Eloquent;

use App\Models\TransactionItem;
use App\Repository\Contracts\TransactionItemRepositoryInterface;

class TransactionItemRepository implements TransactionItemRepositoryInterface
{
    public function byTransaction($transactionId)
    {
        return TransactionItem::where('transaction_id', $transactionId)->get();
    }

    public function create(array $data)
    {
        return TransactionItem::create($data);
    }

    public function update($id, array $data)
    {
        $item = TransactionItem::findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = TransactionItem::findOrFail($id);
        return $item->delete();
    }
}