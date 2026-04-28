<?php

namespace Laymont\PatternRepository\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UpdateAction
{
    /**
     * Execute update.
     */
    public function execute(int|string $id, Model $model, array $attributes): bool
    {
        return DB::transaction(function () use ($id, $model, $attributes) {
            return $model->newQuery()
                ->where('id', $id)
                ->update($attributes) > 0;
        });
    }
}