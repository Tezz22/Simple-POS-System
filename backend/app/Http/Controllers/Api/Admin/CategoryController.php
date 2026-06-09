<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Category\Queries\CategoryQuery;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(CategoryQuery $query): JsonResponse
    {
        try {
            $categories = $query->getAll();
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori'
            ], 500);
        }
    }

    public function show(CategoryQuery $query, $id): JsonResponse
    {
        try {
            $category = $query->getById($id);
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }
    }
}
