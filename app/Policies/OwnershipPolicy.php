<?php

namespace App\Policies;

use App\Models\Ownership;
use App\Models\User;
use App\Models\Artwork;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any ownerships.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the ownership.
     */
    public function view(User $user, Ownership $ownership): bool
    {
        // Admin can view any ownership
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can view their own ownership
        return $user->id === $ownership->owner_id;
    }

    /**
     * Determine whether the user can create ownerships.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can transfer an artwork.
     */
    public function transfer(User $user, Artwork $artwork): bool
    {
        // Admin can transfer any artwork
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can transfer their own artwork
        return $artwork->current_owner && $artwork->current_owner->id === $user->id;
    }

    /**
     * Determine whether the user can update the ownership.
     */
    public function update(User $user, Ownership $ownership): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the ownership.
     */
    public function delete(User $user, Ownership $ownership): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the ownership.
     */
    public function restore(User $user, Ownership $ownership): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the ownership.
     */
    public function forceDelete(User $user, Ownership $ownership): bool
    {
        return $user->isAdmin();
    }
}
