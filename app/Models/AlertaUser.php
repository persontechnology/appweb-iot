<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaUser extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function alerta() {
        return $this->belongsTo(Alerta::class, 'alerta_id', 'id');
    }

}
