<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => [
                'required',
                'exists:transactions,id'
            ],

            'product_id' => [
                'required',
                'exists:products,id'
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
}