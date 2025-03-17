<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'tour_operator_id', 'permission_id','created_at','updated_at'];

    protected $casts = [
        'permission_id' => 'array',
    ];
    
}
