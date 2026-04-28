<?php

namespace Laymont\PatternRepository\Contracts;

use Illuminate\Database\Eloquent\Model;

interface WritableRepositoryInterface
{
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
     * Update multiple records.
     */
    public function updateMany(array $ids, array $attributes): int;

    /**
     * Delete multiple records.
     */
    public function deleteMany(array $ids): int;

    /**
     * Upsert records.
     */
    public function upsert(array $values, array $uniqueBy, array $update = []): int;

    /**
     * First or create record.
     */
    public function firstOrCreate(array $attributes, array $values = []): Model;

    /**
     * Update or create record.
     */
    public function updateOrCreate(array $attributes, array $values = []): Model;
}