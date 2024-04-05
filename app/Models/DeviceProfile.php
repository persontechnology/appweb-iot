<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceProfile extends Model
{
    use HasFactory;

    protected $table='device_profile';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}
