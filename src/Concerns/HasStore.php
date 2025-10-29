<?php

namespace SaKanjo\FilamentAuthPreferences\Concerns;

use Closure;
use SaKanjo\FilamentAuthPreferences\AuthPreferences\CacheStore;
use SaKanjo\FilamentAuthPreferences\Contracts\AuthPreferences;

trait HasStore
{
    protected string|AuthPreferences|Closure|null $store = null;

    public function store(string|AuthPreferences|Closure|null $store): static
    {
        $this->store = $store;

        return $this;
    }

    public function getStore(): string|AuthPreferences
    {
        return $this->evaluate($this->store) ??
            CacheStore::class;
    }
}
