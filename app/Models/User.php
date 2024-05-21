<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    protected $table='user';
    protected $primaryKey = 'id';
    protected $keyType = 'string'; 
    public $incrementing = false;
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->id=Str::uuid();
        });
    }



    // un usuario tiene varios tenanuser
    public function tenantUsers()
    {
        return $this->hasMany(TenantUser::class, 'user_id', 'id');
    }
    

    // un usuario tiene varios entidades
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user', 'user_id', 'tenant_id')
                    ->withTimestamps();
    }

    public function hasTenant($tenantId)
    {
        return $this->tenants()->where('tenant_id', $tenantId)->exists();
    }

    // user -> alerta_user

    public function alertaUser()
    {
        return $this->hasMany(AlertaUser::class, 'user_id', 'id');
    }

    // verificar si el usuario tiene un alerta:
    public function tieneAlerta($alertaId)
    {
        return $this->alertaUser()->where('alerta_id', $alertaId)->exists();
    }
    
}
