<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $fillable = ['name', 'parent_id', 'category_type_id', 'itemcode', 'coa','sequence'];

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

}
