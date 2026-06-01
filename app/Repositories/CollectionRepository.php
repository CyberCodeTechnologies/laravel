<?php

namespace App\Repositories;

use App\Models\Collection as ArtCollection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CollectionRepository extends BaseRepository implements CollectionRepositoryInterface
{
    public function __construct(ArtCollection $model)
    {
        parent::__construct($model);
    }

    public function getActive(): EloquentCollection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getWithArtworkCount(): EloquentCollection
    {
        return $this->model->withCount('artworks')->get();
    }

    public function findBySlug(string $slug): ?ArtCollection
    {
        return $this->model->where('slug', $slug)->first();
    }
}
