<?php

namespace App\Policies;

use App\Models\Dispositivo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DispositivoPolicy
{
    
    public function editar(User $user, Dispositivo $dispositivo): bool
    {
        return $dispositivo->application->tenant_id==$user->tenant_id;
    }
    public function eliminar(User $user, Dispositivo $dispositivo): bool
    {
        return $dispositivo->application->tenant_id==$user->tenant_id;
    }
    
}
