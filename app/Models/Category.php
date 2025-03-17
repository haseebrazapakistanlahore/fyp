<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'is_active', 'type', 'slug', 'image', 'has_sizes', 'has_colors', 'has_color_no','category_order'];

    public function subCategories()
    {
        return $this->hasMany('App\Models\SubCategory');
    }
    
    public function products()
    {
        return $this->belongsTo('App\Models\Product');
    }
    
    public function productCount()
    {
        return Product::where('category_id', $this->id)->where('is_deleted', 0)  ->count();
    }
}
