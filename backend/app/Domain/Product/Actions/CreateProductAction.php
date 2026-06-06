<?php

namespace App\Domain\Product\Actions;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Exception;

class CreateProductAction
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $data)
    {
        // Business Rule: Harga jual tidak boleh < harga beli
        if (isset($data['selling_price']) && isset($data['purchase_price'])) {
            if ($data['selling_price'] < $data['purchase_price']) {
                throw new Exception("Harga jual tidak boleh lebih kecil dari harga beli.");
            }
        }

        return $this->repository->create($data);
    }
}