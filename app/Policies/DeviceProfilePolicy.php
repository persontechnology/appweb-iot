<?php

namespace App\Policies;

use App\Models\DeviceProfile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeviceProfilePolicy
{
    
    public function editar(User $user, DeviceProfile $deviceProfile): bool
    {
        if ($user->hasTenant($deviceProfile->tenant_id)) {
            return true;
        } 
        return false;
    }

    public function eliminar(User $user, DeviceProfile $deviceProfile): bool
    {
        if ($user->hasTenant($deviceProfile->tenant_id)) {
            return true;
        } 
        return false;
    }

    

}
