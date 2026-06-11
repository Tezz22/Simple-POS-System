<?php

namespace App\Http\Controllers\Api\Cashier;

use App\Http\Controllers\Controller;
use App\Domain\Transaction\Actions\AddItemAction;
use App\Domain\Transaction\Actions\UpdateItemAction;
use App\Domain\Transaction\Actions\RemoveItemAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionItemController extends Controller
{
    public function __construct(
        protected AddItemAction $addItemAction,
        protected UpdateItemAction $updateItemAction,
        protected RemoveItemAction $removeItemAction,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $item = $this->addItemAction->execute(
            $request->validated()
        );

        return response()->json([
            'message' => 'Item added',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $item = $this->updateItemAction->execute(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Item updated',
            'data' => $item,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->removeItemAction->execute($id);

        return response()->json([], 204);
    }
}