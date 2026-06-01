<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(): Collection;

    /**
     * Find a record by ID.
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or throw exception.
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update a record.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record.
     */
    public function delete(int $id): bool;

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find record by field value.
     */
    public function findBy(string $field, mixed $value): ?Model;

    /**
     * Get records with relations.
     */
    public function with(array $relations): self;
}
