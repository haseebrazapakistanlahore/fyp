<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['min_amount', 'max_amount', 'start_date', 'end_date', 'discount_percentage', 'image', 'is_active'];
}
