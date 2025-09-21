<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiveItem extends Model
{
    protected $guarded = [];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
