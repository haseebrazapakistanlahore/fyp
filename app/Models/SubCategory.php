<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['id', 'title', 'category_id', 'is_active', 'slug', 'image' ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function subChildCategories()
    {
        return $this->hasMany('App\Models\SubChildCategory');
    }
    
    public function productCount()
    {
        return Product::where('sub_category_id', $this->id)->where('is_deleted', 0)  ->count();
    }
}
