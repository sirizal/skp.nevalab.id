<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPortion extends Model
{
    protected $fillable = [
        'production_id',
        'menu_type_id',
        'portion_count',
        'budget_cost',
        'total_budget_cost',
        'percentage',
        'total_estimated_cost',
        'total_actual_cost',
        'estimated_cost_per_portion',
        'actual_cost_per_portion',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }   

    public function menuType()
    {
        return $this->belongsTo(MenuType::class);
    }
}
