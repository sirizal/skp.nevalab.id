<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'name',
        'production_date',
        'total_budget_cost',
        'total_estimated_cost',
        'total_actual_cost',
        'sr_no',
    ];

    public function menuPlans()
    {
        return $this->hasMany(MenuPlan::class);
    }

    public function menuPortions()
    {
        return $this->hasMany(MenuPortion::class);
    }
}
