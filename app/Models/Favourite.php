<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = ['consumer_id', 'professional_id', 'product_id'];


    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}