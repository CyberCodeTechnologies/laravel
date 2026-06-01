<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find user by slug.
     */
    public function findBySlug(string $slug): ?User;

    /**
     * Get users by role.
     */
    public function getByRole(string $role): Collection;

    /**
     * Get approved users.
     */
    public function getApproved(): Collection;

    /**
     * Get pending users.
     */
    public function getPending(): Collection;

    /**
     * Search users by name or email.
     */
    public function search(string $query): Collection;

    /**
     * Get artists with artwork count.
     */
    public function getArtistsWithArtworkCount(): Collection;

    /**
     * Toggle user approval status.
     */
    public function toggleApproval(int $userId): bool;

    /**
     * Toggle user active status.
     */
    public function toggleActiveStatus(int $userId): bool;
}
