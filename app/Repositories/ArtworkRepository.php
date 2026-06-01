<?php

namespace App\Repositories;

use App\Models\Artwork;
use App\Models\Scopes\ApprovedScope;
use App\Repositories\Interfaces\ArtworkRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ArtworkRepository extends BaseRepository implements ArtworkRepositoryInterface
{
    public function __construct(Artwork $model)
    {
        parent::__construct($model);
    }

    public function getApproved(): Collection
    {
        return $this->model->approved()->get();
    }

    public function getPending(): Collection
    {
        return $this->model->withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'pending')
            ->get();
    }

    public function getByArtist(int $artistId): Collection
    {
        return $this->model->where('artist_id', $artistId)->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();
    }

    public function getFeatured(int $limit = 10): Collection
    {
        return $this->model->where('is_featured', true)
            ->approved()
            ->take($limit)
            ->get();
    }

    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->approved();

        if (isset($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['artist'])) {
            $query->where('artist_id', $filters['artist']);
        }

        if (isset($filters['medium'])) {
            $query->where('medium', $filters['medium']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function toggleApproval(int $artworkId): bool
    {
        $artwork = $this->find($artworkId);
        if ($artwork) {
            $artwork->status = $artwork->status === 'approved' ? 'pending' : 'approved';
            return $artwork->save();
        }
        return false;
    }

    public function incrementViews(int $artworkId): bool
    {
        $artwork = $this->find($artworkId);
        if ($artwork) {
            return $artwork->increment('views_count');
        }
        return false;
    }

    public function getRelated(int $artworkId, int $categoryId, int $limit = 6): Collection
    {
        return $this->model->where('category_id', $categoryId)
            ->where('id', '!=', $artworkId)
            ->approved()
            ->take($limit)
            ->get();
    }

    // Legacy method for backward compatibility
    public function getPendingForModeration(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->withoutGlobalScope(ApprovedScope::class)
            ->where('status', 'pending')
            ->latest()
            ->paginate($perPage);
    }
}
