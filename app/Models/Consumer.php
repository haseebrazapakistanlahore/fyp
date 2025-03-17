<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ConsumerResetPasswordNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;
class Consumer extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Uuids;

    public $incrementing = false;

    protected $fillable = [
        'full_name', 'email', 'password', 'phone', 'profile_image', 'is_active', 'is_deleted', 'last_login', 'token', 'device_type'
    ];

    protected $hidden = [
        'password', 'last_login'
    ];

    public function addresses()
    {
        return $this->hasMany('App\Models\Address', 'consumer_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'consumer_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ConsumerResetPasswordNotification($token));
    }
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['ll' => $this->last_login];
    }

    
    public function _check()
    {
        
        if ($this->is_deleted == 1) 
        return [
            'status' => 5,
            'message' => 'Your Account Has Been Deleted.',
        ];
        
        if ($this->is_active == 0) 
        return [
            'status' => 4,
            'message' => 'Your Account Has Been Suspended.',
        ];

        return null;
    }
}
