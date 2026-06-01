<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Get orders by user.
     */
    public function getByUser(int $userId): Collection;

    /**
     * Get orders by status.
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get orders with artist.
     */
    public function getByArtist(int $artistId): Collection;

    /**
     * Get recent orders.
     */
    public function getRecent(int $limit = 10): Collection;

    /**
     * Get orders with pagination and filters.
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Update order status.
     */
    public function updateStatus(int $orderId, string $status): bool;

    /**
     * Get order statistics.
     */
    public function getStatistics(): array;

    /**
     * Get orders by date range.
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;
}
