<?php

namespace Laymont\PatternRepository\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface CriteriaInterface
{
    /**
     * Apply the criteria to the given query builder.
     */
    public function apply(Builder $query): Builder;
}