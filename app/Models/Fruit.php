<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'unit', 'purchase_price', 'selling_price', 'shelf_life_days'])]
class Fruit extends Model
{
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
