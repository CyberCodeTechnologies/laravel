<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Artwork;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any certificates.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the certificate.
     */
    public function view(User $user, Certificate $certificate): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create certificates.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isArtist();
    }

    /**
     * Determine whether the user can generate a certificate for an artwork.
     */
    public function generateCertificate(User $user, Artwork $artwork): bool
    {
        // Admin can generate certificate for any artwork
        if ($user->isAdmin()) {
            return true;
        }

        // Artist can generate certificate for their own artworks
        return $user->isArtist() && $artwork->artist_id === $user->id;
    }

    /**
     * Determine whether the user can update the certificate.
     */
    public function update(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the certificate.
     */
    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the certificate.
     */
    public function restore(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the certificate.
     */
    public function forceDelete(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }
}
