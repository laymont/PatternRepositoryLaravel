<?php

namespace Laymont\PatternRepository\Concerns;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Config\Repository as ConfigRepository;

trait CacheableRepository
{
    /**
     * @var bool
     */
    protected bool $cacheEnabled = false;

    /**
     * @var int
     */
    protected int $cacheTtl = 60;

    /**
     * @var array
     */
    protected array $cacheTags = [];

    /**
     * @var string|null
     */
    protected ?string $cachePrefix = null;

    /**
     * Boot trait and configure cache settings from config
     */
    protected function bootCacheableRepository(): void
    {
        $config = app(ConfigRepository::class);
        $this->cacheEnabled = $config->get('pattern-repository.enable_cache', false);
        $this->cacheTtl = $config->get('pattern-repository.cache_ttl', 60);
        $this->cacheTags = $config->get('pattern-repository.cache_tags', ['repositories']);
        
        // Au00f1adir un prefijo basado en el nombre del modelo
        if (isset($this->model)) {
            $modelClass = get_class($this->model);
            $this->cachePrefix = strtolower(class_basename($modelClass));
            $this->cacheTags[] = $this->cachePrefix;
        }
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param string $key
     * @param \Closure $callback
     * @param int|null $ttl
     * @return mixed
     */
    protected function cacheResult(string $key, \Closure $callback, int $ttl = null): mixed
    {
        if (!$this->cacheEnabled) {
            return $callback();
        }

        $ttl = $ttl ?? $this->cacheTtl;
        $fullKey = $this->getCacheKey($key);

        return $this->getCacheDriver()
            ->tags($this->cacheTags)
            ->remember($fullKey, $ttl, $callback);
    }

    /**
     * Generate a cache key based on the provided key and prefix
     *
     * @param string $key
     * @return string
     */
    protected function getCacheKey(string $key): string
    {
        return $this->cachePrefix ? "{$this->cachePrefix}:{$key}" : $key;
    }

    /**
     * Clear the cache for the current repository
     *
     * @return bool
     */
    public function clearCache(): bool
    {
        if (!$this->cacheEnabled) {
            return false;
        }

        return $this->getCacheDriver()->tags($this->cacheTags)->flush();
    }

    /**
     * Get the cache driver
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function getCacheDriver()
    {
        return Cache::store();
    }

    /**
     * Enable caching for this repository instance
     *
     * @return self
     */
    public function enableCache(): self
    {
        $this->cacheEnabled = true;
        return $this;
    }

    /**
     * Disable caching for this repository instance
     *
     * @return self
     */
    public function disableCache(): self
    {
        $this->cacheEnabled = false;
        return $this;
    }

    /**
     * Set cache ttl for this repository instance
     *
     * @param int $minutes
     * @return self
     */
    public function setCacheTtl(int $minutes): self
    {
        $this->cacheTtl = $minutes;
        return $this;
    }
}
