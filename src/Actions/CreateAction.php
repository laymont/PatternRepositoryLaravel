<?php

namespace Laymont\PatternRepository\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateAction
{
    /**
     * Execute creation.
     */
    public function execute(Model $model, array $attributes): Model
    {
        return DB::transaction(function () use ($model, $attributes) {
            return $model->create($attributes);
        });
    }
}