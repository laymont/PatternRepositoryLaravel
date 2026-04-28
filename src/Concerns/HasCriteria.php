<?php

namespace Laymont\PatternRepository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laymont\PatternRepository\Contracts\CriteriaInterface;

trait HasCriteria
{
    protected array $criteria = [];

    /**
     * Add a criteria to the repository.
     */
    public function pushCriteria(CriteriaInterface $criteria): static
    {
        $this->criteria[] = $criteria;
        return $this;
    }

    /**
     * Reset all criteria.
     */
    public function resetCriteria(): static
    {
        $this->criteria = [];
        return $this;
    }

    /**
     * Apply all stored criteria to the query builder.
     */
    protected function applyCriteria(Builder $query): Builder
    {
        foreach ($this->criteria as $criteria) {
            $query = $criteria->apply($query);
        }
        
        return $query;
    }
}
