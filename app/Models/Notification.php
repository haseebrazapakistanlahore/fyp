<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['consumer_id', 'professional_id', 'content', 'is_read'];
}
