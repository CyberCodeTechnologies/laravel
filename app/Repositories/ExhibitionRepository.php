<?php

namespace App\Repositories;

use App\Models\Exhibition;
use App\Repositories\Interfaces\ExhibitionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ExhibitionRepository extends BaseRepository implements ExhibitionRepositoryInterface
{
    public function __construct(Exhibition $model)
    {
        parent::__construct($model);
    }

    public function getActive(): Collection
    {
        return $this->model->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_published', true)
            ->get();
    }

    public function getUpcoming(): Collection
    {
        return $this->model->where('start_date', '>', now())
            ->where('is_published', true)
            ->orderBy('start_date')
            ->get();
    }

    public function getPast(): Collection
    {
        return $this->model->where('end_date', '<', now())
            ->where('is_published', true)
            ->orderBy('end_date', 'desc')
            ->get();
    }

    public function findBySlug(string $slug): ?Exhibition
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getWithArtworkCount(): Collection
    {
        return $this->model->withCount('artworks')->get();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('is_published', true)
            ->latest()
            ->paginate($perPage);
    }
}
