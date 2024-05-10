<?php

namespace SaKanjo\FilamentAuthPreferences\Presets;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SaKanjo\FilamentAuthPreferences\Forms\PanelColor;
use SaKanjo\FilamentAuthPreferences\Forms\PanelFont;

class PanelPreset extends Preset
{
    public static function data(): array
    {
        $panel = Filament::getCurrentPanel();

        return [
            'brandName' => $panel->getBrandName(),
            'breadcrumbs' => $panel->hasBreadcrumbs(),
            'colors' => FilamentColor::getColors(),
            'darkMode' => $panel->hasDarkMode(),
            'darkModeForced' => $panel->hasDarkModeForced(),
            'font' => $panel->getFontFamily(),
            'globalSearch' => (bool) $panel->getGlobalSearchProvider(),
            'globalSearchDebounce' => Str::before($panel->getGlobalSearchDebounce(), 'ms'),
            'maxContentWidth' => $panel->getMaxContentWidth(),
            'navigation' => $panel->hasNavigation(),
            'databaseNotifications' => $panel->hasDatabaseNotifications(),
            'databaseNotificationsPolling' => Str::before($panel->getDatabaseNotificationsPollingInterval(), 's'),
            'sidebarWidth' => Str::before($panel->getSidebarWidth(), 'rem'),
            'collapsedSidebarWidth' => Str::before($panel->getCollapsedSidebarWidth(), 'rem'),
            'sidebarCollapsibleOnDesktop' => $panel->isSidebarCollapsibleOnDesktop(),
            'sidebarFullyCollapsibleOnDesktop' => $panel->isSidebarFullyCollapsibleOnDesktop(),
            'collapsibleNavigationGroups' => $panel->hasCollapsibleNavigationGroups(),
            'spa' => $panel->hasSpaMode(),
            'topbar' => $panel->hasTopbar(),
            'topNavigation' => $panel->hasTopNavigation(),
            'unsavedChangesAlerts' => $panel->hasUnsavedChangesAlerts(),
        ];
    }

    public static function schema(): array
    {
        return [
            PanelColor::make(),

            PanelFont::make('font'),

            Forms\Components\TextInput::make('brandName'),

            Forms\Components\TextInput::make('globalSearchDebounce')
                ->suffix('ms')
                ->integer()
                ->minValue(50),

            Forms\Components\Select::make('maxContentWidth')
                ->preload()
                ->options(Arr::mapWithKeys(MaxWidth::cases(), fn (MaxWidth $maxWidth) => [
                    $maxWidth->value => (string) str($maxWidth->name)->headline(),
                ])),

            Forms\Components\TextInput::make('databaseNotificationsPolling')
                ->suffix('s')
                ->integer()
                ->minValue(1),

            Forms\Components\TextInput::make('sidebarWidth')
                ->suffix('rem')
                ->numeric()
                ->minValue(.5)
                ->maxValue(75),

            Forms\Components\TextInput::make('collapsedSidebarWidth')
                ->suffix('rem')
                ->numeric()
                ->minValue(.5)
                ->lt('sidebarWidth'),

            Forms\Components\Fieldset::make('Options')
                ->schema(static::options()),
        ];
    }

    protected static function options(): array
    {
        return [
            Forms\Components\Toggle::make('breadcrumbs'),

            Forms\Components\Toggle::make('darkMode'),

            Forms\Components\Toggle::make('darkModeForced'),

            Forms\Components\Toggle::make('globalSearch'),

            Forms\Components\Toggle::make('navigation'),

            Forms\Components\Toggle::make('databaseNotifications'),

            Forms\Components\Toggle::make('sidebarCollapsibleOnDesktop'),

            Forms\Components\Toggle::make('sidebarFullyCollapsibleOnDesktop'),

            Forms\Components\Toggle::make('collapsibleNavigationGroups'),

            Forms\Components\Toggle::make('spa'),

            Forms\Components\Toggle::make('topbar'),

            Forms\Components\Toggle::make('topNavigation'),

            Forms\Components\Toggle::make('unsavedChangesAlerts'),
        ];
    }

    public static function apply(array $data): void
    {
        $panel = Filament::getCurrentPanel();

        foreach ($data as $method => $value) {
            match ($method) {
                'brandName' => $panel->brandName($value),
                'breadcrumbs' => $panel->breadcrumbs($value),
                'colors' => FilamentColor::register($value),
                'darkMode' => $panel->darkMode($value),
                'darkModeForced' => $panel->darkMode($panel->hasDarkMode(), $value),
                'font' => $panel->font($value),
                'globalSearch' => $panel->globalSearch($value),
                'globalSearchDebounce' => $panel->globalSearchDebounce($value.'ms'),
                'maxContentWidth' => $panel->maxContentWidth($value),
                'navigation' => $panel->navigation($value),
                'databaseNotifications' => $panel->databaseNotifications($value),
                'databaseNotificationsPolling' => $panel->databaseNotificationsPolling($value.'s'),
                'sidebarWidth' => $panel->sidebarWidth($value.'rem'),
                'collapsedSidebarWidth' => $panel->collapsedSidebarWidth($value.'rem'),
                'sidebarCollapsibleOnDesktop' => $panel->sidebarCollapsibleOnDesktop($value),
                'sidebarFullyCollapsibleOnDesktop' => $panel->sidebarFullyCollapsibleOnDesktop($value),
                'collapsibleNavigationGroups' => $panel->collapsibleNavigationGroups($value),
                'spa' => $panel->spa($value),
                'topbar' => $panel->topbar($value),
                'topNavigation' => $panel->topNavigation($value),
                'unsavedChangesAlerts' => $panel->hasSpaMode() ? null : $panel->unsavedChangesAlerts($value),
                default => null
            };
        }
    }
}
