<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function getByBuyer(int $buyerId): Collection
    {
        return $this->model->where('buyer_id', $buyerId)->latest()->get();
    }

    public function getBySeller(int $sellerId): Collection
    {
        return $this->model->where('seller_id', $sellerId)->latest()->get();
    }

    public function getByArtwork(int $artworkId): Collection
    {
        return $this->model->where('artwork_id', $artworkId)->latest()->get();
    }

    public function getCompleted(): Collection
    {
        return $this->model->where('status', 'completed')->latest()->get();
    }

    public function getPending(): Collection
    {
        return $this->model->where('status', 'pending')->latest()->get();
    }

    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['buyer_id'])) {
            $query->where('buyer_id', $filters['buyer_id']);
        }

        if (isset($filters['seller_id'])) {
            $query->where('seller_id', $filters['seller_id']);
        }

        if (isset($filters['artwork_id'])) {
            $query->where('artwork_id', $filters['artwork_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStatistics(): array
    {
        return [
            'total' => $this->model->count(),
            'completed' => $this->model->where('status', 'completed')->count(),
            'pending' => $this->model->where('status', 'pending')->count(),
            'total_volume' => $this->model->where('status', 'completed')->sum('amount'),
            'seller_earnings' => $this->model->where('status', 'completed')->sum('seller_earnings'),
        ];
    }
}
