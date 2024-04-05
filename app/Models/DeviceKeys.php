<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceKeys extends Model
{
    use HasFactory;
    protected $table='device_keys';
    protected $primaryKey = 'dev_eui';
    protected $keyType = 'string';
    public $incrementing = false;
}
