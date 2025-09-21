<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    protected $fillable = [
        'item_id',
        'receive_qty',
        'outgoing_qty',
        'balance_qty'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function setBalanceStock()
    {
        $this->balance_qty = ($this->receive_qty ?? 0) - ($this->outgoing_qty ?? 0);
        $this->save();
    }
}
