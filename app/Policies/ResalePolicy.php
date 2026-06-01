<?php

namespace App\Policies;

use App\Models\Resale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResalePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any resales.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the resale.
     */
    public function view(User $user, Resale $resale): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create resales.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCollector();
    }

    /**
     * Determine whether the user can create a resale for an artwork.
     */
    public function resale(User $user, $artwork): bool
    {
        // Admin can create resale for any artwork
        if ($user->isAdmin()) {
            return true;
        }

        // Collector can create resale for artworks they own
        return $user->isCollector() && $artwork->current_owner && $artwork->current_owner->id === $user->id;
    }

    /**
     * Determine whether the user can update the resale.
     */
    public function update(User $user, Resale $resale): bool
    {
        // Admin can update any resale
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can update their own resale
        return $user->id === $resale->owner_id;
    }

    /**
     * Determine whether the user can delete the resale.
     */
    public function delete(User $user, Resale $resale): bool
    {
        // Admin can delete any resale
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can delete their own resale
        return $user->id === $resale->owner_id;
    }

    /**
     * Determine whether the user can approve the resale.
     */
    public function approve(User $user, Resale $resale): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the resale.
     */
    public function restore(User $user, Resale $resale): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the resale.
     */
    public function forceDelete(User $user, Resale $resale): bool
    {
        return $user->isAdmin();
    }
}
