<?php

namespace Laymont\PatternRepository\Actions;

use Illuminate\Database\Eloquent\Model;

class DeleteAction
{
    /**
     * Execute deletion.
     */
    public function execute(int|string $id, Model $model): bool
    {
        return $model->newQuery()
            ->where('id', $id)
            ->delete() > 0;
    }
}