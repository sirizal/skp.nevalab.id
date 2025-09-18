<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPlan extends Model
{
    protected $fillable = [
        'production_id',
        'category_id',
        'recipe_id',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }   

    public function category()
    {
        return $this->belongsTo(Category::class);
    } 

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }


}
