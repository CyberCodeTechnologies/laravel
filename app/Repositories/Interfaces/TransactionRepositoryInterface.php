<?php

namespace App\Repositories\Interfaces;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get transactions by buyer.
     */
    public function getByBuyer(int $buyerId): Collection;

    /**
     * Get transactions by seller (artist).
     */
    public function getBySeller(int $sellerId): Collection;

    /**
     * Get transactions by artwork.
     */
    public function getByArtwork(int $artworkId): Collection;

    /**
     * Get completed transactions.
     */
    public function getCompleted(): Collection;

    /**
     * Get pending transactions.
     */
    public function getPending(): Collection;

    /**
     * Get transactions with pagination and filters.
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get transaction statistics.
     */
    public function getStatistics(): array;
}
