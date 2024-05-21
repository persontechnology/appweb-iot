<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlertaTipo extends Model
{
    use HasFactory;

    protected $fillbale=[
        'parametro',
        'condicion',
        'valor',
        'alerta_id',
    ];
  // Define la relación inversa
  public function alerta()
  {
      return $this->belongsTo(Alerta::class);
  }

}
