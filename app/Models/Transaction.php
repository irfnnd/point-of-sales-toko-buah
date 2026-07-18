<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['invoice_number', 'user_id', 'transaction_date', 'total_amount', 'paid_amount', 'change_amount', 'status'])]
class Transaction extends Model
{
    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
