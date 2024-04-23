<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationPolicy
{
    
    public function editar(User $user, Application $application): bool
    {
        if ($user->hasTenant($application->tenant_id)) {
            return true;
        } 
        return false;
    }


    public function eliminar(User $user, Application $application): bool
    {
        if ($user->hasTenant($application->tenant_id)) {
            return true;
        } 
        return false;
    }

   

   
}
