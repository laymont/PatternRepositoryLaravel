<?php

namespace Laymont\PatternRepository\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laymont\PatternRepository\Contracts\RepositoryInterface;
use Laymont\PatternRepository\Concerns\HasCriteria;
use Laymont\PatternRepository\Concerns\HandlePerPageTrait;
use Laymont\PatternRepository\Concerns\HasEloquentScopes;

abstract class EloquentRepository implements RepositoryInterface
{
    use HasCriteria, HandlePerPageTrait, HasEloquentScopes;

    public function __construct(
        protected Model $model
    ) {}

    public function find(int|string $id): Model
    {
        return $this->applyCriteria(
            $this->model->newQuery()
        )->findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->applyCriteria(
            $this->model->newQuery()
        )->get();
    }

    public function paginate(int $perPage = 15): mixed
    {
        return $this->applyCriteria(
            $this->model->newQuery()
        )->paginate($perPage);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(int|string $id, array $attributes): bool
    {
        return $this->find($id)->update($attributes);
    }

    public function delete(int|string $id): bool
    {
        return $this->find($id)->delete();
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}