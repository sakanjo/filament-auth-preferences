<?php

namespace SaKanjo\FilamentAuthPreferences\Presets;

abstract class Preset
{
    public static function make(): static
    {
        return app(static::class);
    }

    abstract public static function data(): array;

    abstract public static function components(): array;

    abstract public static function apply(array $data): void;
}
