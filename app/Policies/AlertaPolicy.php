<?php

namespace App\Policies;

use App\Models\Alerta;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlertaPolicy
{
   
    /**
     * Determine whether the user can view the model.
     */
    public function editarHorario(User $user, Alerta $alerta): bool
    {
        if ($user->hasTenant($alerta->application->tenant_id)) {
            return true;
        } 
        return false;
    }

    
}
