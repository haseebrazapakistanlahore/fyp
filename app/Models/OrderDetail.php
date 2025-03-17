<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

    protected $fillable = [ 'id','order_id','product_id', 'product_color_id', 'quantity','unit_price','sub_total','discount','product_request'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }






}
