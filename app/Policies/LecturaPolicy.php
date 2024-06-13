<?php

namespace App\Policies;

use App\Models\Lectura;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LecturaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lectura $lectura): bool
    {
        return $lectura->tenant_id==$user->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lectura $lectura): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lectura $lectura): bool
    {
        return $lectura->tenant_id==$user->tenant_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lectura $lectura): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lectura $lectura): bool
    {
        //
    }
}
