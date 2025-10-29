<?php

namespace SaKanjo\FilamentAuthPreferences\AuthPreferences;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SaKanjo\FilamentAuthPreferences\Contracts\AuthPreferences;

class AuthStore implements AuthPreferences
{
    public static string $key = 'extra.preferences';

    public function get(?string $key = null, mixed $default = null): mixed
    {
        $data = data_get(Auth::user(), static::$key, []);
        $value = $key ? data_get($data, $key) : $data;

        return $value ?? $default;
    }

    public function set(array $data): void
    {
        $key = Str::replace('.', '->', static::$key);

        Auth::user()->update([ // @phpstan-ignore-line
            $key => [
                ...$this->get(default: []),
                ...$data,
            ],
        ]);
    }
}
