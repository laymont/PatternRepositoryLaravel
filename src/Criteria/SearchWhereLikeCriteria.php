<?php

namespace Laymont\PatternRepository\Criteria;

use Illuminate\Database\Eloquent\Builder;

class SearchWhereLikeCriteria implements CriteriaInterface
{
    /**
     * SearchWhereLikeCriteria constructor.
     * 
     * @param string|array $columns Las columnas para buscar
     * @param string $searchTerm El tu00e9rmino de bu00fasqueda
     * @param string $boolean El operador booleano a usar (and/or)
     */
    public function __construct(
        private readonly string|array $columns,
        private readonly string $searchTerm,
        private readonly string $boolean = 'and'
    ) {}
    
    /**
     * Apply the criteria to the given query builder.
     * 
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $columns = is_array($this->columns) ? $this->columns : [$this->columns];
        
        return $query->where(function (Builder $query) use ($columns, $searchTerm) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $query->{$method}($column, 'LIKE', $searchTerm);
            }
        }, null, null, $this->boolean);
    }
}
