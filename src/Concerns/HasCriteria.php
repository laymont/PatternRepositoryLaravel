<?php

namespace Laymont\PatternRepository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Laymont\PatternRepository\Criteria\CriteriaInterface;

trait HasCriteria
{
    protected array $criteria = [];

    /**
     * Add a criteria to the repository.
     *
     * @param CriteriaInterface $criteria
     * @return self
     */
    public function pushCriteria(CriteriaInterface $criteria): self
    {
        $this->criteria[] = $criteria;
        return $this;
    }

    /**
     * Reset all criteria.
     *
     * @return self
     */
    public function resetCriteria(): self
    {
        $this->criteria = [];
        return $this;
    }

    /**
     * Apply all stored criteria to the query builder.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function applyCriteria(Builder $query): Builder
    {
        foreach ($this->criteria as $criteria) {
            $query = $criteria->apply($query);
        }
        
        return $query;
    }
}
