<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $table='tenant';
    protected $primaryKey = 'id';
    protected $keyType = 'string'; 
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->id=Str::uuid();
        });
    }

    // tenant -> tenant_user
    public function tenantUsers()
    {
        return $this->hasMany(TenantUser::class, 'tenant_id', 'id');
    }


    // tenant -> tenant_user -> user
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user', 'tenant_id', 'user_id')
        ->withTimestamps();
    }

    // tenant -> gateway
    public function gateways()
    {
        return $this->hasMany(Gateway::class, 'tenant_id');
    }


    // tenant -> aplication
    public function applications()
    {
        return $this->hasMany(Application::class, 'tenant_id');
    }

    // tenant -> device_profile
    public function deviceProfiles()
    {
        return $this->hasMany(DeviceProfile::class, 'tenant_id');
    }


    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'tenant_id');
    }

    // tenant -> application -> device
    
    
}
