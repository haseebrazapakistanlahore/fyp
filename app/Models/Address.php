<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['consumer_id', 'professional_id', 'city', 'country', 'address', 'address_type'];
}
