<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['id', 'consumer_id', 'professional_id', 'rating', 'comment', 'product_id'];

    public function consumer()
    {
        return $this->belongsTo('App\Models\Consumer', 'consumer_id', 'id');
    }
    
    public function professional()
    {
        return $this->belongsTo('App\Models\Professional', 'professional_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
