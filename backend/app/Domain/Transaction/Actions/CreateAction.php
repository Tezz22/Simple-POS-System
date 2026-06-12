<?php

namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CreateAction
{
    public function __construct(
        protected TransactionRepositoryInterface $repository
    ) {}

    public function execute(array $data)
    {
        $data['invoice_number'] = 'INV-' . now()->format('YmdHis');
        $data['cashier_id'] = Auth::id();
        $data['transaction_date'] = now();

        $data['total_item'] = 0;
        $data['subtotal'] = 0;
        $data['discount_amount'] = 0;
        $data['grand_total'] = 0;
        $data['paid_amount'] = 0;
        $data['change_amount'] = 0;

        $data['payment_method'] = 'cash';
        $data['status'] = 'draft';

        return $this->repository->create($data);
    }
}
