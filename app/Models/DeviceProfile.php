<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeviceProfile extends Model
{
    use HasFactory;

    protected $table='device_profile';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id=Str::uuid();
        });
    }


    // un gateway esta en un tenant 
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
    public function configuration(): HasOne
    {
        return $this->hasOne(Configuration::class, 'device_profile_id');
    }
}
