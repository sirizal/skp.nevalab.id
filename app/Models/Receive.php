<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receive extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'code',
        'receive_date',
        'document_no',
        'document_date',
        'invoice_no',
        'invoice_date',
        'purchase_id',
        'user_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function receiveItems(): HasMany
    {
        return $this->hasMany(ReceiveItem::class);
    }
}
