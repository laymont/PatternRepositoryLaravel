<?php

namespace Laymont\PatternRepository\Tests\Unit\Actions;

use Laymont\PatternRepository\Tests\TestCase;
use Laymont\PatternRepository\Actions\CreateAction;
use Laymont\PatternRepository\Actions\UpdateAction;
use Laymont\PatternRepository\Actions\DeleteAction;
use Laymont\PatternRepository\Tests\TestModel;

class ActionsTest extends TestCase
{
    private TestModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new TestModel();
    }

    public function test_create_action_returns_model(): void
    {
        $action = new CreateAction();

        $result = $action->execute($this->model, [
            'name' => 'New User',
            'email' => 'new_' . uniqid() . '@example.com',
        ]);

        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertDatabaseHas('test_models', ['name' => 'New User']);
    }

    public function test_update_action_returns_bool(): void
    {
        $created = $this->createTestModel();

        $action = new UpdateAction();

        $result = $action->execute($created->id, $this->model, ['name' => 'Updated']);

        $this->assertTrue($result);
    }

    public function test_update_action_returns_false_for_nonexistent(): void
    {
        $action = new UpdateAction();

        $result = $action->execute(9999, $this->model, ['name' => 'Updated']);

        $this->assertFalse($result);
    }

    public function test_delete_action_returns_bool(): void
    {
        $created = $this->createTestModel();

        $action = new DeleteAction();

        $result = $action->execute($created->id, $this->model);

        $this->assertTrue($result);
    }

    public function test_delete_action_returns_false_for_nonexistent(): void
    {
        $action = new DeleteAction();

        $result = $action->execute(9999, $this->model);

        $this->assertFalse($result);
    }

    public function test_delete_action_accepts_string_id(): void
    {
        $created = $this->createTestModel();

        $action = new DeleteAction();

        $result = $action->execute((string) $created->id, $this->model);

        $this->assertTrue($result);
    }
}