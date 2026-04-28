<?php

namespace Laymont\PatternRepository\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        Schema::create('test_models', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    protected function createTestModel(array $attributes = []): TestModel
    {
        return TestModel::create(array_merge([
            'name' => 'Test User',
            'email' => 'test_' . uniqid() . '@example.com',
            'active' => true,
        ], $attributes));
    }

    protected function createTestModels(int $count, array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        $models = new \Illuminate\Database\Eloquent\Collection();

        for ($i = 0; $i < $count; $i++) {
            $models->push($this->createTestModel($attributes));
        }

        return $models;
    }
}