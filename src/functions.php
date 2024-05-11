<?php

namespace SaKanjo\FilamentAuthPreferences;

use BackedEnum;
use Illuminate\Support\Arr;
use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

if (! function_exists('SaKanjo\FilamentAuthPreferences\prefers')) {
    function prefers(string $key, mixed $default = null): mixed
    {
        return AuthPreferences::get($key, $default);
    }
}

if (! function_exists('SaKanjo\FilamentAuthPreferences\get_enum_options')) {
    function get_enum_options(string $enum): mixed
    {
        return Arr::mapWithKeys($enum::cases(), fn (mixed $case) => [
            ($case instanceof BackedEnum ? $case->value : $case->name) => (string) str($case->name)->headline(),
        ]);
    }
}
