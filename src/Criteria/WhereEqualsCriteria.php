<?php

namespace Laymont\PatternRepository\Criteria;

use Illuminate\Database\Eloquent\Builder;

class WhereEqualsCriteria implements CriteriaInterface
{
    /**
     * WhereEqualsCriteria constructor.
     * 
     * @param string $column El nombre de la columna para filtrar
     * @param mixed $value El valor para comparar
     */
    public function __construct(
        private readonly string $column,
        private readonly mixed $value
    ) {}
    
    /**
     * Apply the criteria to a query builder.
     * 
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        return $query->where($this->column, $this->value);
    }
}
