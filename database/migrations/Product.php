<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
    use Uuids;

    // protected $appends = array('category_name','sub_category_name','sub_child_category_name', 'rating', 'no_of_reviews');
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    protected $fillable = ['id', 'prod_id', 'title', 'description', 'shipping_cost', 'price', 'size', 'category_id', 'sub_category_id', 'sub_child_category_id', 'offer_available', 'offer_price', 'min_order_level', 'available_quantity', 'product_type', 'thumbnail', 'is_deleted', 'color_no', 'is_featured'];

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id' );
    }
   
    public function subCategory()
    {
        return $this->hasOne('App\Models\SubCategory', 'id', 'sub_category_id' );
    }

    public function subChildCategory()
    {
        return $this->hasOne('App\Models\SubChildCategory', 'id', 'sub_child_category_id' );
    }

    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function getCategoryNameAttribute()
    {
        return Category::find($this->category_id)->title;
    }

    public function getSubCategoryNameAttribute()
    {
        $subCat = SubCategory::find($this->sub_category_id);
        
        if ($subCat != null) {
            return $subCat->title;
        } else {
            return null;
        }
    }

    public function getSubChildCategoryNameAttribute()
    {
        $subChlidCat = SubChildCategory::find($this->sub_child_category_id);
        
        if ($subChlidCat != null) {
            return $subChlidCat->title;
        } else {
            return null;
        }
    }
    
    public function getRatingAttribute()
    {
        $rating = Review::select(DB::raw('count(id) as total, sum(rating) as sum'))->where('product_id', $this->id)->first();
        if ($rating->total > 0) {
            return ceil($rating->sum/$rating->total);
        }else{
            return 0;
        }
    }
 
    public function getNoOfReviewsAttribute()
    {
        return Review::where('product_id', $this->id)->count();
    }
    
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'product_id', 'id');
    }
    
    public function colors()
    {
        return $this->hasMany('App\Models\ProductColor', 'product_id', 'id');
    }
     
    public function categoryHasColors($id)
    {
        return (boolean) Category::select('has_colors')->where('id', $id)->first()->has_colors;
    }
}
