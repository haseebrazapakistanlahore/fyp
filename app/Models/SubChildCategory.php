<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubChildCategory extends Model
{
    protected $fillable = ['id', 'title', 'category_id', 'sub_category_id', 'is_active', 'image'];

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function productCount()
    {
        return Product::where('sub_child_category_id', $this->id)->where('is_deleted', 0)  ->count();
    }

}
