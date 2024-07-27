<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PuntosLocalizacion extends Model
{
    use HasFactory;
    protected $table = 'puntos_localizaciones';    

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:00',
        'dev_eui' => 'string',
        'data' => 'array',
    ];
}
