<?php

namespace SaKanjo\FilamentAuthPreferences\Forms;

use Filament\Forms;
use Filament\Schemas;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class PanelColor extends Schemas\Components\Tabs
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->statePath('colors')
            ->columnSpanFull()
            ->components($this->getSchema(...));
    }

    private function getSchema(): array
    {
        $filamentColors = FilamentColor::getColors();
        $allColors = Color::all();

        $namedColors = Arr::mapWithKeys($filamentColors, fn (array $shades, string $color) => [
            $color => array_search($shades, $allColors),
        ]);

        return Arr::map($filamentColors, fn (array $shades, string $color) => Schemas\Components\Tabs\Tab::make(Str::headline($color))
            ->badgeColor($color)
            ->badge(__(Str::headline($namedColors[$color] ?? null)))
            ->schema([
                Forms\Components\Select::make($color)
                    ->required()
                    ->preload()
                    ->searchable()
                    ->formatStateUsing(fn () => $namedColors[$color] ?? null)
                    ->dehydrateStateUsing(fn (string $state) => $allColors[$state])
                    ->hiddenLabel()
                    ->allowHtml()
                    ->options(Arr::mapWithKeys($allColors, fn (array $shades, string $color) => [
                        $color => Blade::render($this->template(), [
                            'color' => __($color),
                            'shades' => $shades,
                        ]),
                    ])),
            ])
        );
    }

    protected function template(): string
    {
        return <<<'BLADE'
            <span class='flex items-center gap-x-4'>
                <span class='rounded-full size-4' style='background-color: rgb({{ $shades[600] }});'></span>
                <span>{{ __(Str::headline($color)) }}</span>
            </span>
        BLADE;
    }
}
