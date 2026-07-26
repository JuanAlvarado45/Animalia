<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Mascota;
use App\Models\User;

class MascotaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mascota $mascota): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Mascota $mascota): bool
    {
        return $user->id === $mascota->user_id;
    }

    public function delete(User $user, Mascota $mascota): bool
    {
        return $user->id === $mascota->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mascota $mascota): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mascota $mascota): bool
    {
        return false;
    }
}
