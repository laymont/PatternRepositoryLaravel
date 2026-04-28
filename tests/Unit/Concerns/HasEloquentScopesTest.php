<?php

namespace Laymont\PatternRepository\Tests\Unit\Concerns;

use Laymont\PatternRepository\Tests\TestCase;
use Laymont\PatternRepository\Concerns\HasEloquentScopes;
use Laymont\PatternRepository\Tests\TestModel;

class HasEloquentScopesTest extends TestCase
{
    public function test_returns_self_from_withScopes(): void
    {
        $model = new TestModel();

        $repository = new class ($model) {
            use HasEloquentScopes;

            public function __construct(public TestModel $model) {}
        };

        $result = $repository->withScopes();

        $this->assertSame($repository, $result);
    }

    public function test_returns_model_from_getModel(): void
    {
        $model = new TestModel();

        $repository = new class ($model) {
            use HasEloquentScopes;

            public function __construct(public TestModel $model) {}
        };

        $result = $repository->getModel();

        $this->assertSame($model, $result);
    }
}