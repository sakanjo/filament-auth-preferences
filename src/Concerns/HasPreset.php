<?php

namespace SaKanjo\FilamentAuthPreferences\Concerns;

use Closure;
use SaKanjo\FilamentAuthPreferences\Presets\PanelPreset;
use SaKanjo\FilamentAuthPreferences\Presets\Preset;

trait HasPreset
{
    protected Preset|Closure|null $preset = null;

    public function preset(Preset|Closure $preset): static
    {
        $this->preset = $preset;

        return $this;
    }

    public function getPreset(): ?Preset
    {
        return $this->evaluate($this->preset) ??
            PanelPreset::make();
    }
}
