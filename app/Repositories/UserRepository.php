<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findBySlug(string $slug): ?User
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->get();
    }

    public function getApproved(): Collection
    {
        return $this->model->where('is_approved', true)->get();
    }

    public function getPending(): Collection
    {
        return $this->model->where('is_approved', false)->get();
    }

    public function search(string $query): Collection
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();
    }

    public function getArtistsWithArtworkCount(): Collection
    {
        return $this->model->where('role', 'artist')
            ->where('is_approved', true)
            ->withCount('artworks')
            ->get();
    }

    public function toggleApproval(int $userId): bool
    {
        $user = $this->find($userId);
        if ($user) {
            $user->is_approved = !$user->is_approved;
            return $user->save();
        }
        return false;
    }

    public function toggleActiveStatus(int $userId): bool
    {
        $user = $this->find($userId);
        if ($user) {
            $user->is_active = !$user->is_active;
            return $user->save();
        }
        return false;
    }

    // Legacy method for backward compatibility
    public function findByRole(string $role): Collection
    {
        return $this->getByRole($role);
    }

    // Legacy method for backward compatibility
    public function findPendingArtists(): Collection
    {
        return $this->model->where('role', 'artist')
            ->where('is_approved', false)
            ->get();
    }
}
