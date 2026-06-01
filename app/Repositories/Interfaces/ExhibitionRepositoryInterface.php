<?php

namespace App\Repositories\Interfaces;

use App\Models\Exhibition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExhibitionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get active exhibitions.
     */
    public function getActive(): Collection;

    /**
     * Get upcoming exhibitions.
     */
    public function getUpcoming(): Collection;

    /**
     * Get past exhibitions.
     */
    public function getPast(): Collection;

    /**
     * Find exhibition by slug.
     */
    public function findBySlug(string $slug): ?Exhibition;

    /**
     * Get exhibitions with artwork count.
     */
    public function getWithArtworkCount(): Collection;

    /**
     * Get exhibitions with pagination.
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;
}
