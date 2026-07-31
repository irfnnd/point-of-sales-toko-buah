<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['fruit_id', 'supplier_id', 'type', 'quantity', 'unit_price', 'note', 'recorded_at', 'expired_at'])]
class Stock extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'recorded_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function getExpiryStatusAttribute(): ?string
    {
        if ($this->type !== 'in' || ! $this->expired_at) {
            return null;
        }

        $now = now()->startOfDay();
        $expiredDate = $this->expired_at->startOfDay();

        if ($expiredDate->lessThanOrEqualTo($now)) {
            return 'expired'; // Busuk / Kedaluwarsa
        }

        $diffDays = (int) $now->diffInDays($expiredDate, false);

        if ($diffDays <= 3) {
            return 'near_expiry'; // Hampir Busuk (1-3 hari)
        }

        return 'fresh'; // Segar
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
