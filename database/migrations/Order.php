<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    protected $fillable = ['id', 'consumer_id', 'professional_id', 'net_total', 'delivery_charges', 'gross_total', 'discount_id', 'discount_amount', 'order_status', 'payment_status', 'coupon_code', 'coupon_discount_amount', 'address_id', 'tracking_id', 'payment_method', 'invoice_id', 'fix_discount'];

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function address()
    {
        return $this->hasOne('App\Models\Address', 'id', 'address_id');
    }
}
