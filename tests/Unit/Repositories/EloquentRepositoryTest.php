<?php

namespace Laymont\PatternRepository\Tests\Unit\Repositories;

use Laymont\PatternRepository\Tests\TestCase;
use Laymont\PatternRepository\Repositories\EloquentRepository;
use Laymont\PatternRepository\Tests\TestModel;
use Illuminate\Support\Collection;

class TestRepository extends EloquentRepository
{
    public function __construct(TestModel $model)
    {
        parent::__construct($model);
    }
}

class EloquentRepositoryTest extends TestCase
{
    protected TestRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TestRepository(new TestModel());
    }

    public function test_can_be_instantiated(): void
    {
        $this->assertInstanceOf(EloquentRepository::class, $this->repository);
    }

    public function test_find_returns_model(): void
    {
        $created = $this->createTestModel();

        $found = $this->repository->find($created->id);

        $this->assertInstanceOf(TestModel::class, $found);
        $this->assertEquals($created->id, $found->id);
    }

    public function test_find_throws_when_not_found(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->find(9999);
    }

    public function test_all_returns_collection(): void
    {
        $this->createTestModels(3);

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_create_returns_model(): void
    {
        $result = $this->repository->create([
            'name' => 'New User',
            'email' => 'new_' . uniqid() . '@example.com',
        ]);

        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertDatabaseHas('test_models', ['name' => 'New User']);
    }

    public function test_update_returns_bool(): void
    {
        $created = $this->createTestModel();

        $result = $this->repository->update($created->id, ['name' => 'Updated']);

        $this->assertTrue($result);
    }

    public function test_delete_returns_bool(): void
    {
        $created = $this->createTestModel();

        $result = $this->repository->delete($created->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('test_models', ['id' => $created->id]);
    }

    public function test_paginate_returns_paginator(): void
    {
        $this->createTestModels(15);

        $result = $this->repository->paginate(5);

        $this->assertTrue($result->total() > 10);
    }

    public function test_query_returns_builder(): void
    {
        $result = $this->repository->query();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }

    public function test_find_accepts_string_id(): void
    {
        $created = $this->createTestModel();

        $found = $this->repository->find((string) $created->id);

        $this->assertEquals($created->id, $found->id);
    }
}