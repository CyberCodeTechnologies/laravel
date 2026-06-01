<?php

namespace App\Repositories;

use App\Models\Wishlist;
use App\Repositories\Interfaces\WishlistRepositoryInterface;

class WishlistRepository extends BaseRepository implements WishlistRepositoryInterface
{
    public function __construct(Wishlist $model)
    {
        parent::__construct($model);
    }

    public function getOrCreateForUser(int $userId): Wishlist
    {
        return $this->model->firstOrCreate(['user_id' => $userId]);
    }

    public function getWithItems(int $wishlistId): ?Wishlist
    {
        return $this->model->with('items.artwork')->find($wishlistId);
    }

    public function addArtwork(int $wishlistId, int $artworkId): bool
    {
        $wishlist = $this->find($wishlistId);
        if ($wishlist) {
            return $wishlist->artworks()->syncWithoutDetaching([$artworkId]) !== false;
        }
        return false;
    }

    public function removeArtwork(int $wishlistId, int $artworkId): bool
    {
        $wishlist = $this->find($wishlistId);
        if ($wishlist) {
            return $wishlist->artworks()->detach($artworkId) !== false;
        }
        return false;
    }

    public function hasArtwork(int $wishlistId, int $artworkId): bool
    {
        $wishlist = $this->find($wishlistId);
        if ($wishlist) {
            return $wishlist->artworks()->where('artwork_id', $artworkId)->exists();
        }
        return false;
    }

    public function toggleArtwork(int $wishlistId, int $artworkId): bool
    {
        if ($this->hasArtwork($wishlistId, $artworkId)) {
            return $this->removeArtwork($wishlistId, $artworkId);
        }
        return $this->addArtwork($wishlistId, $artworkId);
    }
}
