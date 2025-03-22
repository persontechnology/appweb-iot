<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'application_id', 'device_profile_id'];

    protected $casts = [
        'application_id' => 'string',  // Convierte 'gateway_id' a tipo 'string'
        'device_profile_id' => 'string',
    ];

    public function rules()
    {
        return $this->hasMany(ConfigurationRule::class);
    }

    public function deviceProfile()
    {
        return $this->belongsTo(DeviceProfile::class, 'device_profile_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * Método estático para asegurarse de que cada DeviceProfile tenga una configuración única.
     */
    public static function ensureConfigurationExists($applicationId, $deviceProfileId)
    {
        return self::firstOrCreate([
            'application_id' => $applicationId,
            'device_profile_id' => $deviceProfileId,
        ]);
    }
}

