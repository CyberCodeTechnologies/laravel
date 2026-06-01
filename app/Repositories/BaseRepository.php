<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;
    protected array $withRelations = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->applyRelations()->get();
    }

    public function find(int $id): ?Model
    {
        return $this->applyRelations()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->applyRelations()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->find($id);
        if ($model) {
            return $model->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyRelations()->latest()->paginate($perPage);
    }

    public function findBy(string $field, mixed $value): ?Model
    {
        return $this->applyRelations()->where($field, $value)->first();
    }

    public function with(array $relations): self
    {
        $this->withRelations = $relations;
        return $this;
    }

    protected function applyRelations(): Builder
    {
        $query = $this->model->query();
        
        if (!empty($this->withRelations)) {
            $query->with($this->withRelations);
        }
        
        return $query;
    }

    protected function resetRelations(): void
    {
        $this->withRelations = [];
    }
}
