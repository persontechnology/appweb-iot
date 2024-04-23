<?php

namespace App\Policies;

use App\Models\Gateway;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GatewayPolicy
{
   
    public function actualizar(User $user, Gateway $gateway): bool
    {
        if ($user->hasTenant($gateway->tenant_id)) {
            return true;
        } 
        return false;
    }


    public function eliminar(User $user, Gateway $gateway): bool
    {
        if ($user->hasTenant($gateway->tenant->id)) {
            return true;
        } 
        return false;
    }

    

}
