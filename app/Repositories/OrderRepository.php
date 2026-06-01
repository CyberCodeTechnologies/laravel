<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->latest()->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->latest()->get();
    }

    public function getByArtist(int $artistId): Collection
    {
        return $this->model->whereHas('items', function ($query) use ($artistId) {
            $query->whereHas('artwork', function ($q) use ($artistId) {
                $q->where('artist_id', $artistId);
            });
        })->latest()->get();
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->model->latest()->take($limit)->get();
    }

    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['artist_id'])) {
            $query->whereHas('items', function ($q) use ($filters) {
                $q->whereHas('artwork', function ($artworkQuery) use ($filters) {
                    $artworkQuery->where('artist_id', $filters['artist_id']);
                });
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $order = $this->find($orderId);
        if ($order) {
            $order->status = $status;
            return $order->save();
        }
        return false;
    }

    public function getStatistics(): array
    {
        return [
            'total' => $this->model->count(),
            'pending' => $this->model->where('status', 'pending')->count(),
            'processing' => $this->model->where('status', 'processing')->count(),
            'completed' => $this->model->where('status', 'completed')->count(),
            'cancelled' => $this->model->where('status', 'cancelled')->count(),
            'total_revenue' => $this->model->where('status', 'completed')->sum('total'),
        ];
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();
    }
}
