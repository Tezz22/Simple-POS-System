<?php

namespace App\Domain\Transaction\Actions;

use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\TransactionItemRepositoryInterface;

class PayAction
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected TransactionItemRepositoryInterface $itemRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(string $transactionId)
    {
        return DB::transaction(function () use ($transactionId) {

            $transaction = $this->transactionRepository->find($transactionId);

            $items = $this->itemRepository
                ->byTransaction($transactionId);

            foreach ($items as $item) {

                $product = $this->productRepository
                    ->find($item->product_id);

                $this->productRepository->update(
                    $product->id,
                    [
                        'stock' => $product->stock - $item->quantity
                    ]
                );
            }

            return $this->transactionRepository
                ->pay($transactionId);
        });
    }
}