<?php

namespace Laymont\PatternRepository\Criteria;

use Illuminate\Database\Eloquent\Builder;

interface CriteriaInterface
{
    /**
     * Apply the criteria to the given query builder.
     *
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder;
}
