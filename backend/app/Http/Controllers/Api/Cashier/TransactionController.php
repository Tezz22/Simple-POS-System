<?php

namespace App\Http\Controllers\Api\Cashier;

use App\Http\Controllers\Controller;
use App\Domain\Transaction\Actions\CreateAction;
use App\Domain\Transaction\Actions\PayAction;
use App\Domain\Transaction\Actions\CancelAction;
use App\Domain\Transaction\Queries\TransactionQuery;
use App\Http\Resources\Transaction\TransactionCollection;
use App\Http\Resources\Transaction\TransactionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionQuery $query,
        protected CreateAction $createAction,
        protected PayAction $payAction,
        protected CancelAction $cancelAction,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => new TransactionCollection(
                $this->query->getAll()
            )
        ]);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => new TransactionResource(
                $this->query->getById($id)
            )
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $transaction = $this->createAction->execute([
            'invoice' => 'INV-' . now()->timestamp,
            'cashier_id' => auth()->id(),
            'total' => 0,
        ]);

        return response()->json([
            'message' => 'Transaction created',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    public function pay(string $id): JsonResponse
    {
        $transaction = $this->payAction->execute($id);

        return response()->json([
            'message' => 'Transaction paid',
            'data' => $transaction,
        ]);
    }

    public function cancel(string $id): JsonResponse
    {
        $transaction = $this->cancelAction->execute($id);

        return response()->json([
            'message' => 'Transaction cancelled',
            'data' => $transaction,
        ]);
    }
}