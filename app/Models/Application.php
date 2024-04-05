<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $table='application';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;



    protected $fillable=[
        'tenant_id',
        // 'created_at',
        // 'updated_at',
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


}
