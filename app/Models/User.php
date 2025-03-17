<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Uuids;
    use Notifiable;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password', 'phone', 'is_active', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function hasPermission($permission) {
        // dd($permission);
        return (bool) $this->permissions->where('slug', $permission)->count();
    }

    public function getUserPermissions() {
        $perm = '';
        foreach ($this->permissions as $permission) {
            $perm .=$permission->name .", ";
        }
        return rtrim(trim($perm), ',');
    }
}
