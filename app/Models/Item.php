<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }
    
    public function category()
    {        
        return $this->belongsTo(Category::class);
    }
}
