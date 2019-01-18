<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Permission extends Model
{
    protected $fillable = ['name', 'description'];
    protected $hidden = ['pivot'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function scopeUsers($query, $id)
    {
        return $query->select('users.id', 'users.name', 'users.email', 'users.created_at')->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                        ->join('roles', 'permission_role.role_id', '=', 'roles.id')
                        ->join('users', 'users.role_id', '=', 'permission_role.role_id')
                        ->where('permissions.id', $id);
    }

    public function getCreatedAtAttribute($value) 
    {
        return Carbon::parse($value)->format('d M Y \a\t H:m');
    }

    public function getUpdatedAtAttribute($value) 
    {
        return Carbon::parse($value)->format('d M Y \a\t H:m');
    }
}
