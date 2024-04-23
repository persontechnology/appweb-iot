<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TenantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function asignarTenant(User $user,Tenant $tenant): bool
    {
        // Verificar si el usuario tiene el tenant específico
        if ($user->hasTenant($tenant->id)) {
            return true;
        }

        // Si el usuario no tiene el tenant específico, puedes lanzar una excepción o manejar el caso según tus necesidades
        return false;
    }


}
