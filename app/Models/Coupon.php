<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['id', 'coupon_code', 'offer_id', 'is_used'];

    public function offer()
    {
        return $this->belongsTo('App\Models\Offer');
    }
}
