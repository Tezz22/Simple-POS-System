<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,

            'cashier_id' => $this->cashier_id,

            'transaction_date' => $this->transaction_date,

            'total_item' => $this->total_item,

            'subtotal' => $this->subtotal,

            'discount_amount' => $this->discount_amount,

            'grand_total' => $this->grand_total,

            'paid_amount' => $this->paid_amount,

            'change_amount' => $this->change_amount,

            'payment_method' => $this->payment_method,

            'status' => $this->status,

            'notes' => $this->notes,

            'cashier' => $this->cashier,

            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                    'product' => [
                        'id' => $item->product?->id,
                        'name' => $item->product?->name,
                        'code' => $item->product?->code,
                    ],
                ];
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
