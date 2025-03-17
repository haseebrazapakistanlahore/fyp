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
    
    protected $fillable = ['id', 'consumer_id', 'professional_id', 'net_total', 'delivery_charges', 'gross_total', 'discount_id', 'discount_amount', 'order_status', 'payment_status', 'coupon_code', 'coupon_discount_amount', 'address_id', 'tracking_id', 'payment_method', 'invoice_id', 'fix_discount', 'is_deleted'];

    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }
    
    public function consumer()
    {
        return $this->belongsTo('App\Models\Consumer', 'consumer_id', 'id');
    }
    
    public function professional()
    {
        return $this->belongsTo('App\Models\Professional', 'professional_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\Address', 'id', 'address_id');
    }
}
