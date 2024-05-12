<?php

namespace SaKanjo\FilamentAuthPreferences;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAuthPreferencesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-auth-preferences';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasInstallCommand(fn (InstallCommand $command) => $command
                ->publishConfigFile()
                ->askToStarRepoOnGitHub('sakanjo/filament-auth-preferences')
            );
    }
}
