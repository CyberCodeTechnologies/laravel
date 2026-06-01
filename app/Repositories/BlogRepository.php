<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function getPublished(): Collection
    {
        return $this->model->where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest()
            ->get();
    }

    public function getFeatured(int $limit = 5): Collection
    {
        return $this->model->where('is_featured', true)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->take($limit)
            ->latest()
            ->get();
    }

    public function findBySlug(string $slug): ?Blog
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getByCategory(string $category): Collection
    {
        return $this->model->where('category', $category)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest()
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest()
            ->get();
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest()
            ->paginate($perPage);
    }
}
