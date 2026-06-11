<?php

namespace App\Http\Controllers\Api\Cashier;

use App\Http\Controllers\Controller;
use App\Domain\Transaction\Actions\PrintReceiptAction;
use App\Http\Resources\Receipt\ReceiptResource;
use Illuminate\Http\JsonResponse;

class ReceiptController extends Controller
{
    public function __construct(
        protected PrintReceiptAction $printReceiptAction
    ) {}

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => new ReceiptResource(
                $this->printReceiptAction->execute($id)
            )
        ]);
    }
}