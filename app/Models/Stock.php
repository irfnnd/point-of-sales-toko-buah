<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['fruit_id', 'supplier_id', 'type', 'quantity', 'unit_price', 'note', 'recorded_at'])]
class Stock extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'recorded_at' => 'datetime',
        ];
    }

    public function fruit(): BelongsTo
    {
        return $this->belongsTo(Fruit::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
