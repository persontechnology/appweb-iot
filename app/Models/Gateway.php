<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class Gateway extends Model
{
    use HasFactory,HasUuids;
    protected $table='gateway';
    protected $primaryKey = 'gateway_id';
    protected $keyType = 'string';
    public $incrementing = false;

    

    protected $fillable=[
        'gateway_id', //id_puerta de enlace
        'tenant_id', //id_inquilino
        'created_at', //creado_en
        'updated_at', //actualizado_en
        'last_seen_at', //visto_por_ultima_vez
        'name', //nombre
        'description', //descripcion
        'latitude', //latitud
        'longitude', //longitud
        'altitude', //altitud
        'stats_interval_secs', //segundos_intervalo_estadisticas
        'tls_certificate', //tls_certificado
        'tags', //etiquetas
        'properties', //propiedades
    ];
    
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($modelo) {
    //         $modelo->tenant_id = Uuid::uuid4()->toString();
    //     });
    // }

}
