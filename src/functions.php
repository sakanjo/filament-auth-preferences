<?php

namespace SaKanjo\FilamentAuthPreferences;

use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

if (! function_exists('SaKanjo\FilamentAuthPreferences\prefers')) {
    function prefers(string $key, mixed $default = null): mixed
    {
        return AuthPreferences::get($key, $default);
    }
}
