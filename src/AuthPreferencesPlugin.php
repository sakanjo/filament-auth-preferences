<?php

namespace SaKanjo\FilamentAuthPreferences;

use Filament\Contracts\Plugin;
use Filament\Events\ServingFilament;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

class AuthPreferencesPlugin implements Plugin
{
    use Concerns\HasPreset;
    use Concerns\WithVisibility;
    use EvaluatesClosures;

    public function getId(): string
    {
        return 'sakanjo/auth-preferences';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        // @phpstan-ignore-next-line
        return filament(
            app(static::class)->getId()
        );
    }

    public function register(Panel $panel): void
    {
        if (! $this->isVisible()) {
            return;
        }

        Event::listen(ServingFilament::class, function (): void {
            if (Auth::check() && Filament::hasPlugin($this->getId())) {
                AuthPreferences::apply();
            }
        });

        $panel
            ->pages([
                Pages\AuthPreferencesPage::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
