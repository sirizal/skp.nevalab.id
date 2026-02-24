<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'standard_price',
        'packing_unit',
        'is_active',
        'is_stock_item',
        'uom_id',
        'category_id',
        'image_path',
        'barcode',
        'buying_price',
        'last_purchase_price',
    ];

    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function materialRequests(): HasMany
    {
        return $this->hasMany(MaterialRequest::class);
    }
}
