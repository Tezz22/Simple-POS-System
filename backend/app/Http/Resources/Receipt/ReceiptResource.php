<?php

namespace App\Http\Resources\Receipt;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'transaction' => $this['transaction'],
            'items' => $this['items'],
        ];
    }
}