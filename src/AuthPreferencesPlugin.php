<?php

namespace SaKanjo\FilamentAuthPreferences;

use Filament\Contracts\Plugin;
use Filament\Events\ServingFilament;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use SaKanjo\FilamentAuthPreferences\Contracts\AuthPreferences as AuthPreferencesContract;
use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

class AuthPreferencesPlugin implements Plugin
{
    use Concerns\HasPreset;
    use Concerns\HasStore;
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

        App::bind(AuthPreferencesContract::class,
            is_string($store = $this->getStore()) ? $store : fn () => $store
        );

        Event::listen(ServingFilament::class, function (): void {
            if (Auth::check() && Filament::hasPlugin($this->getId())) {
                $data = AuthPreferences::get();

                $this->getPreset()->apply($data);
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
