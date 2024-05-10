<?php

namespace SaKanjo\FilamentAuthPreferences\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use SaKanjo\FilamentAuthPreferences\AuthPreferencesPlugin;
use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

/**
 * @property Form $form
 */
class AuthPreferencesPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'livewire.auth-preferences.index';

    public function getTitle(): string|Htmlable
    {
        return __('filament-auth-preferences::default.title');
    }

    public function getHeading(): string|Htmlable
    {
        return __('filament-auth-preferences::default.heading');
    }

    public static function getSlug(): string
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
            ...(AuthPreferencesPlugin::get()->getPreset()?->data() ?? []),
            ...AuthPreferences::get(),
        ];

        $this->form->fill($data);
    }

    protected function getSchemaActions(): Forms\Components\Actions
    {
        return Forms\Components\Actions::make([
            Forms\Components\Actions\Action::make('save')
                ->submit('edit'),

            Forms\Components\Actions\Action::make('reset')
                ->color('gray')
                ->action($this->fillForm(...)),

            Forms\Components\Actions\Action::make('clear')
                ->disabled(fn () => empty(AuthPreferences::get()))
                ->color('danger')
                ->action(function () {
                    AuthPreferences::clear();
                    $this->reload();
                })
                ->extraAttributes([
                    'class' => 'ml-auto',
                ]),
        ])
            ->columnSpanFull();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->columns([
                'sm' => 2,
            ])
            ->schema([
                ...(AuthPreferencesPlugin::get()->getPreset()?->schema() ?? []),
                $this->getSchemaActions(),
            ]);
    }

    public function submit(): void
    {
        $original = AuthPreferences::get();
        $new = $this->form->getState();

        $data = collect(array_diff_multidimensional($new, $original))
            ->filter(fn (mixed $value) => $value !== null)
            ->mapWithKeys(fn (mixed $value, string $key): array => [
                $key => is_array($value)
                    ? array_merge($original[$key] ?? [], $value)
                    : $value,
            ])
            ->toArray();

        AuthPreferences::store($data);

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
