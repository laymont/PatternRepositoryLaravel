<?php

namespace Laymont\PatternRepository\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Config\Repository as ConfigRepository;

trait Cacheable
{
    protected bool $cacheEnabled = false;
    protected int $cacheTtl = 60;
    protected string $cachePrefix = '';
    protected array $cacheTags = [];

    /**
     * Boot trait and configure cache settings from config.
     */
    protected function bootCacheable(): void
    {
        $config = app(ConfigRepository::class);
        $this->cacheEnabled = $config->get('pattern-repository.enable_cache', false);
        $this->cacheTtl = $config->get('pattern-repository.cache_ttl', 60);
        $this->cacheTags = $config->get('pattern-repository.cache_tags', ['repositories']);

        if (isset($this->model)) {
            $modelClass = get_class($this->model);
            $this->cachePrefix = strtolower(Str::afterLast($modelClass, '\\'));
            $this->cacheTags[] = $this->cachePrefix;
        }
    }

    /**
     * Enable caching.
     */
    public function enableCache(int $ttl = 60): static
    {
        $this->cacheEnabled = true;
        $this->cacheTtl = $ttl;
        return $this;
    }

    /**
     * Disable caching.
     */
    public function disableCache(): static
    {
        $this->cacheEnabled = false;
        return $this;
    }

    /**
     * Remember in cache.
     */
    protected function remember(string $key, callable $callback): mixed
    {
        if (!$this->cacheEnabled) {
            return $callback();
        }

        return Cache::remember(
            $this->getCacheKey($key),
            $this->cacheTtl,
            $callback
        );
    }

    /**
     * Generate cache key.
     */
    protected function getCacheKey(string $key): string
    {
        return $this->cachePrefix
            ? "{$this->cachePrefix}:{$key}"
            : $key;
    }

    /**
     * Invalidate cache.
     */
    public function invalidateCache(): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        return Cache::forget($this->getCacheKey('*'));
    }
}