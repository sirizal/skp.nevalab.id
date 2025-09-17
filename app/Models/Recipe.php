<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = ['name', 'category_id'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }   
}
