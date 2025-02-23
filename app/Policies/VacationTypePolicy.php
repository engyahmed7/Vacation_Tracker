<?php

namespace App\Policies;

use App\Models\VacationType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class VacationTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('supervisor') || $user->hasRole('hr');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,  VacationType $vacationType): bool
    {
        return $user->hasRole('admin') && $vacationType->created_by == $user->id
        || $user->hasRole('supervisor') && $vacationType->created_by == $user->id
        || $user->hasRole('hr') && $vacationType->created_by == $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VacationType $vacationType): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,VacationType $vacationType): bool
    {

        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VacationType $vacationType): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VacationType $vacationType): bool
    {
        return $user->hasRole('admin');
    }
}


