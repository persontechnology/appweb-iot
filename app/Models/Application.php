<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $table='application';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable=[
        'tenant_id',
        'name',
        'description',
        'mqtt_tls_cert',
        'tags'
    ];

    protected static function booted()
    {
        static::creating(function ($application) {
            $application->id=Str::uuid();
        });
    }

     // una aplicacion esta en un tenant
    public function tenant(): BelongsTo
    {
    return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // formateando fecha
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // application -> device
    public function dispositivos()
    {
        return $this->hasMany(Dispositivo::class, 'application_id');
    }
    public function configuraciones(): HasMany
    {
        return $this->hasMany(Configuracion::class, 'application_id')->orderBy('valor');
    }
}
