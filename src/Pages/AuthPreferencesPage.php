<?php

namespace SaKanjo\FilamentAuthPreferences\Pages;

use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use SaKanjo\FilamentAuthPreferences\AuthPreferencesPlugin;
use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

/**
 * @property-read Schemas\Schema $form
 */
class AuthPreferencesPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament-auth-preferences::index';

    public function getTitle(): string|Htmlable
    {
        return __('filament-auth-preferences::default.title');
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return config('filament-auth-preferences.slug');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = [
            ...(AuthPreferencesPlugin::get()->getPreset()->data()),
            ...AuthPreferences::get(),
        ];

        $this->form->fill($data);
    }

    protected function getSchemaActions(): Schemas\Components\Actions
    {
        return Schemas\Components\Actions::make([
            Actions\Action::make('save')
                ->translateLabel()
                ->submit('edit'),

            Actions\Action::make('reset')
                ->translateLabel()
                ->color('gray')
                ->action($this->fillForm(...)),

            Actions\Action::make('clear')
                ->translateLabel()
                ->disabled(fn () => empty(AuthPreferences::get()))
                ->color('danger')
                ->action(function () {
                    AuthPreferences::set([]);

                    $this->reload();
                }),
        ])
            ->columnSpan([
                'default' => 'full',
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns([
                'sm' => 2,
            ])
            ->components([
                ...(AuthPreferencesPlugin::get()->getPreset()->components()),
                $this->getSchemaActions(),
            ]);
    }

    public function submit(): void
    {
        $original = AuthPreferences::get();
        $new = $this->form->getState();

        $data = collect(array_diff_multidimensional($new, $original))
            ->filter(fn (mixed $value, string $key) => $value !== ($original[$key] ?? null))
            ->mapWithKeys(fn (mixed $value, string $key): array => [
                $key => is_array($value)
                    ? array_merge($original[$key] ?? [], $value)
                    : $value,
            ])
            ->toArray();

        AuthPreferences::set($data);

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();

        $this->reload();
    }

    private function reload(): void
    {
        $this->js('window.location.reload()');
    }
}
