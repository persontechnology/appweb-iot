<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table='tenant';
    // protected $primaryKey = 'id'; // Nombre del campo UUID en la base de datos
    // public $incrementing = false; // Desactivar la autoincrementación
    protected $keyType = 'string'; // Tipo de dato del campo UUID
    
}
