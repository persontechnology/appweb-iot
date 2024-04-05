<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationIntegration extends Model
{
    use HasFactory;
    protected $table='application_integration';
    protected $primaryKey = 'application_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'configuration' => 'array',
    ];
}
