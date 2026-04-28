<?php

namespace Laymont\PatternRepository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Laymont\PatternRepository\Contracts\CriteriaInterface as BaseCriteriaInterface;

interface CriteriaInterface extends BaseCriteriaInterface
{
    /**
     * Apply the criteria to the given query builder.
     */
    public function apply(Builder $query): Builder;
}
