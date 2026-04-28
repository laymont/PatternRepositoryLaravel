<?php

namespace Laymont\PatternRepository\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * Find record by ID.
     */
    public function find(int|string $id): Model;

    /**
     * Find all records.
     */
    public function all(): Collection;

    /**
     * Find with pagination.
     */
    public function paginate(int $perPage = 15): mixed;

    /**
     * Create new record.
     */
    public function create(array $attributes): Model;

    /**
     * Update existing record.
     */
    public function update(int|string $id, array $attributes): bool;

    /**
     * Delete record.
     */
    public function delete(int|string $id): bool;

    /**
     * Get base query builder.
     */
    public function query();
}