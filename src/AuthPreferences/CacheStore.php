<?php

namespace SaKanjo\FilamentAuthPreferences\AuthPreferences;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use SaKanjo\FilamentAuthPreferences\Contracts\AuthPreferences;

class CacheStore implements AuthPreferences
{
    public static ?string $driver = null;

    public function get(?string $key = null, mixed $default = null): mixed
    {
        $data = Cache::driver(static::$driver)->get($this->getKey(), []);
        $value = $key ? data_get($data, $key) : $data;

        return $value ?? $default;
    }

    public function set(array $data): void
    {
        Cache::driver(static::$driver)->put($this->getKey(), [
            ...$this->get(default: []),
            ...$data,
        ]);
    }

    public function getKey(): string
    {
        $userKey = Auth::user()->getKey(); // @phpstan-ignore-line
        $panelId = Filament::getCurrentPanel()->getId();

        return "filament-auth-preferences:panel.$panelId,user.$userKey";
    }
}
