<?php

namespace App\Policies;

use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TenantUserPolicy
{
   
    public function editar(User $user, TenantUser $tenantUser): bool
    {
        if($tenantUser->tenant_id==$user->tenant_id){
            return true;
        }
        return false;
    }
}
