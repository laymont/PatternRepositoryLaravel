<?php

namespace Laymont\PatternRepository\Tests\Unit\Concerns;

use Laymont\PatternRepository\Tests\TestCase;
use Laymont\PatternRepository\Concerns\HasCriteria;
use Laymont\PatternRepository\Criteria\WhereEqualsCriteria;
use Laymont\PatternRepository\Tests\TestModel;
use Illuminate\Database\Eloquent\Builder;

class HasCriteriaTest extends TestCase
{
    public function test_push_criteria_returns_self(): void
    {
        $model = new TestModel();

        $repository = new class ($model) {
            use HasCriteria;

            public function __construct(public TestModel $model) {}
        };

        $criteria = new WhereEqualsCriteria('name', 'Test');
        $result = $repository->pushCriteria($criteria);

        $this->assertSame($repository, $result);
    }

    public function test_reset_criteria_returns_self(): void
    {
        $model = new TestModel();

        $repository = new class ($model) {
            use HasCriteria;

            public function __construct(public TestModel $model) {}
        };

        $result = $repository->resetCriteria();

        $this->assertSame($repository, $result);
    }

    public function test_apply_criteria_modifies_query(): void
    {
        $model = new TestModel();
        $criteria = new WhereEqualsCriteria('active', true);

        $repository = new class ($model, $criteria) {
            use HasCriteria;

            public function __construct(public TestModel $model, public $testCriteria)
            {
                $this->pushCriteria($testCriteria);
            }

            public function applyToQuery($query)
            {
                return $this->applyCriteria($query);
            }
        };

        $query = $model->newQuery();
        $appliedQuery = $repository->applyToQuery($query);

        $this->assertInstanceOf(Builder::class, $appliedQuery);
    }
}