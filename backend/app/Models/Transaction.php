<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 
        'cashier_id', 
        'transaction_date', 
        'total_item', 
        'subtotal', 
        'discount_amount', 
        'grand_total', 
        'paid_amount', 
        'change_amount', 
        'payment_method', 
        'status', 
        'notes'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_item' => 'integer',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
