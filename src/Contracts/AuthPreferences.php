<?php

namespace SaKanjo\FilamentAuthPreferences\Contracts;

interface AuthPreferences
{
    public function get(?string $key = null, mixed $default = null): mixed;

    public function set(array $data): void;
}
