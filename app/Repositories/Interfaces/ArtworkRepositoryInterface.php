<?php

namespace App\Repositories\Interfaces;

use App\Models\Artwork;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArtworkRepositoryInterface extends RepositoryInterface
{
    /**
     * Get approved artworks.
     */
    public function getApproved(): Collection;

    /**
     * Get pending artworks.
     */
    public function getPending(): Collection;

    /**
     * Get artworks by artist.
     */
    public function getByArtist(int $artistId): Collection;

    /**
     * Get artworks by category.
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Search artworks.
     */
    public function search(string $query): Collection;

    /**
     * Get featured artworks.
     */
    public function getFeatured(int $limit = 10): Collection;

    /**
     * Get artworks with pagination and filters.
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Toggle artwork approval status.
     */
    public function toggleApproval(int $artworkId): bool;

    /**
     * Increment artwork view count.
     */
    public function incrementViews(int $artworkId): bool;

    /**
     * Get related artworks.
     */
    public function getRelated(int $artworkId, int $categoryId, int $limit = 6): Collection;
}
