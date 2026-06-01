<?php

namespace App\Policies;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArtworkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any artworks.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the artwork.
     */
    public function view(User $user, Artwork $artwork): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create artworks.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isArtist();
    }

    /**
     * Determine whether the user can update the artwork.
     */
    public function update(User $user, Artwork $artwork): bool
    {
        // Admin can update any artwork
        if ($user->isAdmin()) {
            return true;
        }

        // Artist can update their own artworks
        return $user->id === $artwork->artist_id;
    }

    /**
     * Determine whether the user can delete the artwork.
     */
    public function delete(User $user, Artwork $artwork): bool
    {
        // Admin can delete any artwork
        if ($user->isAdmin()) {
            return true;
        }

        // Artist can delete their own artworks
        return $user->id === $artwork->artist_id;
    }

    /**
     * Determine whether the user can restore the artwork.
     */
    public function restore(User $user, Artwork $artwork): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the artwork.
     */
    public function forceDelete(User $user, Artwork $artwork): bool
    {
        return $user->isAdmin();
    }
}
