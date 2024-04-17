<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    use HasFactory;

    protected $table = 'tenant_user'; // Nombre de la tabla pivote
    public $incrementing = false; // Si tus claves primarias son UUID
    protected $keyType = 'string'; // Tipo de clave primaria

    protected $fillable = [
        'tenant_id',
        'user_id',
        // Otros campos si los tienes
    ];

      // Relación con el modelo Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
