<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * Get categories with artwork count.
     */
    public function getWithArtworkCount(): Collection;

    /**
     * Get active categories.
     */
    public function getActive(): Collection;

    /**
     * Find category by slug.
     */
    public function findBySlug(string $slug): ?Category;

    /**
     * Get featured categories.
     */
    public function getFeatured(int $limit = 10): Collection;
}
