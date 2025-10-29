<?php

namespace SaKanjo\FilamentAuthPreferences\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(?string $key = null, mixed $default = null)
 * @method static void set(array $data)
 */
class AuthPreferences extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SaKanjo\FilamentAuthPreferences\Contracts\AuthPreferences::class;
    }
}
