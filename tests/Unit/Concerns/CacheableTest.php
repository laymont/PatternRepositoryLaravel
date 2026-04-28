<?php

namespace Laymont\PatternRepository\Tests\Unit\Concerns;

use Laymont\PatternRepository\Tests\TestCase;
use Laymont\PatternRepository\Concerns\Cacheable;
use Laymont\PatternRepository\Tests\TestModel;

class CacheableTest extends TestCase
{
    public function test_enable_cache_returns_self(): void
    {
        $repository = new class {
            use Cacheable;

            public ?TestModel $model = null;

            public function callEnableCache(int $ttl = 60): static
            {
                return $this->enableCache($ttl);
            }
        };

        $result = $repository->callEnableCache();

        $this->assertSame($repository, $result);
    }

    public function test_disable_cache_returns_self(): void
    {
        $repository = new class {
            use Cacheable;

            public ?TestModel $model = null;

            public function callDisableCache(): static
            {
                return $this->disableCache();
            }
        };

        $repository->enableCache();
        $result = $repository->callDisableCache();

        $this->assertSame($repository, $result);
    }

    public function test_get_cache_key_with_prefix(): void
    {
        $model = new TestModel();
        $model->name = 'Test';

        $repository = new class ($model) {
            use Cacheable;

            public function __construct(public TestModel $model)
            {
                $this->cachePrefix = 'test';
            }

            public function callGetCacheKey(string $key): string
            {
                return $this->getCacheKey($key);
            }
        };

        $result = $repository->callGetCacheKey('users');

        $this->assertEquals('test:users', $result);
    }

    public function test_get_cache_key_without_prefix(): void
    {
        $repository = new class {
            use Cacheable;

            public ?TestModel $model = null;

            public function callGetCacheKey(string $key): string
            {
                return $this->getCacheKey($key);
            }
        };

        $result = $repository->callGetCacheKey('users');

        $this->assertEquals('users', $result);
    }

    public function test_invalidate_cache_returns_false_when_disabled(): void
    {
        $repository = new class {
            use Cacheable;

            public ?TestModel $model = null;
        };

        $result = $repository->invalidateCache();

        $this->assertFalse($result);
    }

    public function test_remember_executes_callback_when_disabled(): void
    {
        $executed = false;

        $repository = new class {
            use Cacheable;

            public ?TestModel $model = null;

            public function callRemember(string $key, callable $callback): mixed
            {
                return $this->remember($key, $callback);
            }
        };

        $repository->callRemember('test', function () use (&$executed) {
            $executed = true;
            return 'cached value';
        });

        $this->assertTrue($executed);
    }
}