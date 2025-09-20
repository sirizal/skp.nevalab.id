<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    protected $fillable = [
        'production_id',
        'item_id',
        'uom_id',
        'standard_price',
        'request_quantity',
        'used_quantity',
        'returned_quantity',
        'total_estimated_cost',
        'total_actual_cost',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }   

    public function uom()
    {
        return $this->belongsTo(Uom::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function getTotalEstimatedCostAttribute()
    {
        return $this->standard_price * $this->request_quantity;
    }
    
    public function getTotalActualCostAttribute()
    {
        return $this->standard_price * $this->used_quantity;
    }

}
