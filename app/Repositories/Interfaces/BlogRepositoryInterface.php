<?php

namespace App\Repositories\Interfaces;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface extends RepositoryInterface
{
    /**
     * Get published blogs.
     */
    public function getPublished(): Collection;

    /**
     * Get featured blogs.
     */
    public function getFeatured(int $limit = 5): Collection;

    /**
     * Find blog by slug.
     */
    public function findBySlug(string $slug): ?Blog;

    /**
     * Get blogs by category.
     */
    public function getByCategory(string $category): Collection;

    /**
     * Search blogs.
     */
    public function search(string $query): Collection;

    /**
     * Get blogs with pagination.
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator;
}
