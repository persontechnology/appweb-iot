<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table='device';
    protected $primaryKey = 'dev_eui';
    protected $keyType = 'string';
    public $incrementing = false;


    // un dispositivo, o device tiene varias lecturas
    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'dev_eui', 'dev_eui');
    }




}
