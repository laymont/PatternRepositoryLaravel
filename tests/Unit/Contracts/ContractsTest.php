<?php

namespace Laymont\PatternRepository\Tests\Unit\Contracts;

use Laymont\PatternRepository\Tests\TestCase;
use Laymont\PatternRepository\Contracts\RepositoryInterface;
use Laymont\PatternRepository\Contracts\ReadableRepositoryInterface;
use Laymont\PatternRepository\Contracts\WritableRepositoryInterface;
use Laymont\PatternRepository\Contracts\CriteriaInterface;
use Laymont\PatternRepository\Criteria\WhereEqualsCriteria;
use Laymont\PatternRepository\Tests\TestModel;
use Illuminate\Database\Eloquent\Builder;

class ContractsTest extends TestCase
{
    public function test_repository_interface_exists(): void
    {
        $this->assertTrue(interface_exists(RepositoryInterface::class));
    }

    public function test_readable_repository_interface_exists(): void
    {
        $this->assertTrue(interface_exists(ReadableRepositoryInterface::class));
    }

    public function test_writable_repository_interface_exists(): void
    {
        $this->assertTrue(interface_exists(WritableRepositoryInterface::class));
    }

    public function test_criteria_interface_exists(): void
    {
        $this->assertTrue(interface_exists(CriteriaInterface::class));
    }

    public function test_criteria_interface_has_apply_method(): void
    {
        $reflection = new \ReflectionClass(CriteriaInterface::class);
        
        $this->assertTrue($reflection->hasMethod('apply'));
        
        $method = $reflection->getMethod('apply');
        $this->assertEquals('apply', $method->getName());
    }

    public function test_where_equals_criteria_implements_interface(): void
    {
        $criteria = new WhereEqualsCriteria('name', 'Test');
        
        $this->assertInstanceOf(CriteriaInterface::class, $criteria);
    }

    public function test_where_equals_criteria_apply_returns_builder(): void
    {
        $model = new TestModel();
        $query = $model->newQuery();
        
        $criteria = new WhereEqualsCriteria('name', 'Test');
        
        $result = $criteria->apply($query);
        
        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_repository_interface_has_required_methods(): void
    {
        $reflection = new \ReflectionClass(RepositoryInterface::class);
        $methods = ['find', 'all', 'paginate', 'create', 'update', 'delete', 'query'];
        
        foreach ($methods as $method) {
            $this->assertTrue(
                $reflection->hasMethod($method),
                "RepositoryInterface should have method: {$method}"
            );
        }
    }

    public function test_readable_repository_interface_has_required_methods(): void
    {
        $reflection = new \ReflectionClass(ReadableRepositoryInterface::class);
        $methods = ['find', 'findOrFail', 'findBy', 'all', 'paginate', 'query', 'count', 'exists'];
        
        foreach ($methods as $method) {
            $this->assertTrue(
                $reflection->hasMethod($method),
                "ReadableRepositoryInterface should have method: {$method}"
            );
        }
    }

    public function test_writable_repository_interface_has_required_methods(): void
    {
        $reflection = new \ReflectionClass(WritableRepositoryInterface::class);
        $methods = ['create', 'update', 'delete', 'updateMany', 'deleteMany', 'upsert', 'firstOrCreate', 'updateOrCreate'];
        
        foreach ($methods as $method) {
            $this->assertTrue(
                $reflection->hasMethod($method),
                "WritableRepositoryInterface should have method: {$method}"
            );
        }
    }
}