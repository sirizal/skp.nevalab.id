<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'user_id',
        'uom_id',
        'adjustment_date',
        'adjustment_qty',
        'adjustment_price',
        'adjustment_reason',
        'adjustment_type',
        'remain_qty',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(Uom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
