<?php

namespace App\Repositories\Interfaces;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CollectionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get active collections.
     */
    public function getActive(): EloquentCollection;

    /**
     * Get collections with artwork count.
     */
    public function getWithArtworkCount(): EloquentCollection;

    /**
     * Find collection by slug.
     */
    public function findBySlug(string $slug): ?Collection;
}
