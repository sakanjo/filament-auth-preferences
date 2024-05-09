<?php

namespace SaKanjo\FilamentAuthPreferences;

use Filament\Facades\Filament;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthPreferences
{
    public function cache(): Repository
    {
        return Cache::driver('file');
    }

    public function getKey(): string
    {
        $userKey = Auth::user()->getKey(); // @phpstan-ignore-line
        $panelId = Filament::getCurrentPanel()->getId();

        return "filament-auth-preferences:panel.$panelId,user.$userKey";
    }

    public function apply(): void
    {
        AuthPreferencesPlugin::get()
            ->getPreset()?->apply($this->get());
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        $data = $this->cache()->get($this->getKey(), []);
        $value = $key ? data_get($data, $key) : $data;

        return $value ?? $default;
    }

    public function store(array $data): void
    {
        $this->cache()->put($this->getKey(), [
            ...$this->get(default: []),
            ...$data,
        ]);
    }

    public function clear(): void
    {
        $this->cache()->forget($this->getKey());
    }
}
