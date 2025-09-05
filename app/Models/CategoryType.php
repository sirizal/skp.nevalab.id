<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryType extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
