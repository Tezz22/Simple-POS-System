<?php

namespace App\Repository\Eloquent;

use App\Models\Product;
use App\Repository\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        // Relasi BelongsTo ke Category (categories.id -> products.category_id)
        return Product::with('category')->latest()->paginate(10);
    }

    public function find($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function deactivate($id)
    {
        $product = $this->find($id);
        $product->update(['is_active' => false]);
        return $product;
    }

    public function lowStock()
    {
        return Product::whereRaw('stock <= min_stock')->get();
    }
}