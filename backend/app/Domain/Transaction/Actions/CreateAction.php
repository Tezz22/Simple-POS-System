<?php

namespace App\Domain\Transaction\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repository\Contracts\ProductRepositoryInterface;
use App\Repository\Contracts\TransactionRepositoryInterface;
use App\Repository\Contracts\TransactionItemRepositoryInterface;

class CreateAction
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected TransactionItemRepositoryInterface $itemRepository,
        protected ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {

            $subtotal = 0;
            $totalItem = 0;

            foreach ($data['items'] as $item) {
                $product = $this->productRepository->find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }

                $subtotal += $product->selling_price * $item['quantity'];
                $totalItem += $item['quantity'];
            }

            $transaction = $this->transactionRepository->create([
                'invoice_number'   => 'INV-' . now()->format('YmdHis'),
                'cashier_id'       => Auth::id(),
                'transaction_date' => now(),
                'total_item'       => $totalItem,
                'subtotal'         => $subtotal,
                'discount_amount'  => 0,
                'grand_total'      => $subtotal,
                'paid_amount'      => $data['cash_received'],
                'change_amount'    => $data['cash_received'] - $subtotal,
                'payment_method'   => 'cash',
                'status'           => 'paid',
            ]);

            foreach ($data['items'] as $item) {
                $product = $this->productRepository->find($item['product_id']);

                $this->itemRepository->create([
                    'transaction_id'  => $transaction->id,
                    'product_id'      => $product->id,
                    'qty'             => $item['quantity'],
                    'price'           => $product->selling_price,
                    'discount_amount' => 0,
                    'subtotal'        => $product->selling_price * $item['quantity'],
                ]);

                // Kurangi stok
                $product->decrement('stock', $item['quantity']);
            }

            return $transaction;
        });
    }
}
