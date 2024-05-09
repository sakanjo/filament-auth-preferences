<?php

namespace SaKanjo\FilamentAuthPreferences\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Cache\Repository cache()
 * @method static string getKey()
 * @method static void apply()
 * @method static mixed get(?string $key = null, mixed $default = null)
 * @method static void store(array $data)
 * @method static void clear()
 *
 * @see \SaKanjo\FilamentAuthPreferences\AuthPreferences
 */
class AuthPreferences extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SaKanjo\FilamentAuthPreferences\AuthPreferences::class;
    }
}
