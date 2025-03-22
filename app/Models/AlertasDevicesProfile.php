<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertasDevicesProfile extends Model
{
    use HasFactory;

    protected $table = 'alerta_device_profile';
    protected $fillable = [
        'alerta_id',
        'device_profile_id',
    ];


    // Relación con Alerta
    public function alerta()
    {
        return $this->belongsTo(Alerta::class);
    }

    // Relación con TipoDispositivo
    public function deviceprofile(): BelongsTo
    {
        return $this->belongsTo(DeviceProfile::class, 'device_profile_id');
    }
}
