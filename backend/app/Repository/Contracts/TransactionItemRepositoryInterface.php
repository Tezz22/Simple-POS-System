<?php

namespace App\Repository\Contracts;

interface TransactionItemRepositoryInterface
{
    public function byTransaction($transactionId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}