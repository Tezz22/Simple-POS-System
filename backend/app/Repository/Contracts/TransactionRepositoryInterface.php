<?php

namespace App\Repository\Contracts;

interface TransactionRepositoryInterface
{
    public function all();
    public function find($id);
    public function findByInvoice($invoice);
    public function create(array $data);
    public function update($id, array $data);
    public function cancel($id);
    public function pay($id);
    public function byDateRange($startDate, $endDate);
    public function byCashier($cashierId);
}