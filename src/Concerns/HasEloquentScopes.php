<?php

namespace Laymont\PatternRepository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasEloquentScopes
{
    /**
     * Apply model's global scopes.
     */
    public function withScopes(): static
    {
        return $this;
    }

    /**
     * Apply specific scope.
     */
    public function scope(string $scopeName, mixed ...$args): static
    {
        if (method_exists($this->model, $scopeName)) {
            $this->model = $this->model->{$scopeName}(...$args);
        }
        return $this;
    }

    /**
     * Remove all global scopes.
     */
    public function withoutScopes(): static
    {
        $this->model = $this->model->withoutGlobalScopes();
        return $this;
    }

    /**
     * Remove specific global scope.
     */
    public function withoutScope(string $scopeClass): static
    {
        $this->model = $this->model->withoutGlobalScope($scopeClass);
        return $this;
    }

    /**
     * Get current model with scopes applied.
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}