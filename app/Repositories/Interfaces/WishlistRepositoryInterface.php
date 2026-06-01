<?php

namespace App\Repositories\Interfaces;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

interface WishlistRepositoryInterface extends RepositoryInterface
{
    /**
     * Get or create wishlist for user.
     */
    public function getOrCreateForUser(int $userId): Wishlist;

    /**
     * Get wishlist with items.
     */
    public function getWithItems(int $wishlistId): ?Wishlist;

    /**
     * Add artwork to wishlist.
     */
    public function addArtwork(int $wishlistId, int $artworkId): bool;

    /**
     * Remove artwork from wishlist.
     */
    public function removeArtwork(int $wishlistId, int $artworkId): bool;

    /**
     * Check if artwork is in wishlist.
     */
    public function hasArtwork(int $wishlistId, int $artworkId): bool;

    /**
     * Toggle artwork in wishlist.
     */
    public function toggleArtwork(int $wishlistId, int $artworkId): bool;
}
