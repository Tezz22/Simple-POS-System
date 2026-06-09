<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Domain\Product\Queries\ProductQuery;
use App\Domain\Product\Actions\CreateProductAction;
use App\Domain\Product\Actions\UpdateProductAction;
use App\Domain\Product\Actions\DeactivateProductAction;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(ProductQuery $query): JsonResponse
    {
        $products = $query->getAll();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        try {
            $product = $action->execute($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function show(ProductQuery $query, $id): JsonResponse
    {
        try {
            $product = $query->getById($id);
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
    }

    public function update($id, StoreProductRequest $request, UpdateProductAction $action): JsonResponse
    {
        try {
            $product = $action->execute($id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function deactivate($id, DeactivateProductAction $action): JsonResponse
    {
        try {
            $product = $action->execute($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dinonaktifkan',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}