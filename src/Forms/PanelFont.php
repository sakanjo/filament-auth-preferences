<?php

namespace SaKanjo\FilamentAuthPreferences\Forms;

use Filament\Forms;
use Illuminate\Support\Arr;

class PanelFont extends Forms\Components\Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->allowHtml()
            ->preload()
            ->options(Arr::mapWithKeys($this->getFonts(), fn (string $value, string $key) => [
                $key => "<span style='font-family: $key;'>$value</span>",
            ]));
    }

    protected function getFonts(): array
    {
        return [
            'sans-serif' => 'Sans-serif',
            'serif' => 'Serif',
            'mono' => 'Monospace',
            'Inter' => 'Inter',
        ];
    }
}
