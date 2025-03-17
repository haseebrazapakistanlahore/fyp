<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['offer_name', 'prefix', 'start_date', 'end_date', 'discount_percentage', 'is_active'];

    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon');
    }
}
