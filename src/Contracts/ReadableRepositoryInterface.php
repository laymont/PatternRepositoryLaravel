<?php

namespace Laymont\PatternRepository\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ReadableRepositoryInterface
{
    /**
     * Find by ID.
     */
    public function find(int|string $id): ?Model;

    /**
     * Find or fail by ID.
     */
    public function findOrFail(int|string $id): Model;

    /**
     * Find first by criteria.
     */
    public function findBy(array $criteria): ?Model;

    /**
     * Get all records.
     */
    public function all(): Collection;

    /**
     * Get with pagination.
     */
    public function paginate(int $perPage = 15): mixed;

    /**
     * Apply custom query.
     */
    public function query(): Builder;

    /**
     * Count records.
     */
    public function count(): int;

    /**
     * Check if record exists.
     */
    public function exists(array $criteria = []): bool;
}