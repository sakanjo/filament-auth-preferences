<?php

namespace SaKanjo\FilamentAuthPreferences\Presets;

use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordCheckboxPosition;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use SaKanjo\FilamentAuthPreferences\Facades\AuthPreferences;

use function SaKanjo\FilamentAuthPreferences\get_enum_options;

class TablePreset extends Preset
{
    public static array $paginationPageOptions = [5, 10, 25, 50, 'all'];

    public static function data(): array
    {
        $table = Table::make(new ListRecords);

        return [
            'defaultPaginationPageOption' => $table->getDefaultPaginationPageOption(),
            'paginationPageOptions' => $table->getPaginationPageOptions(),
            'poll' => Str::before($table->getPollingInterval(), 's'),
            'searchDebounce' => Str::before($table->getSearchDebounce(), 'ms'),
            'searchPlaceholder' => $table->getSearchPlaceholder(),
            'defaultSortDirection' => $table->getDefaultSortDirection(),
            'columnToggleFormColumns' => $table->getColumnToggleFormColumns(),
            'columnToggleFormMaxHeight' => Str::before($table->getColumnToggleFormMaxHeight(), 'px'),
            'columnToggleFormWidth' => $table->getColumnToggleFormWidth(),
            'actionsColumnLabel' => $table->getActionsColumnLabel(),
            'actionsAlignment' => $table->getActionsAlignment(),
            'actionsPosition' => $table->getActionsPosition()->name,
            'recordCheckboxPosition' => $table->getRecordCheckboxPosition()->name,
            'emptyStateDescription' => $table->getEmptyStateDescription(),
            'emptyStateHeading' => null,
            'emptyStateIcon' => $table->getEmptyStateIcon(),
            'filtersFormColumns' => $table->getFiltersFormColumns(),
            'filtersFormMaxHeight' => Str::before($table->getFiltersFormMaxHeight(), 'px'),
            'filtersFormWidth' => $table->getFiltersFormWidth(),
            'filtersLayout' => $table->getFiltersLayout()->name,
            'description' => $table->getDescription(),
            'heading' => $table->getHeading(),
            'headerActionsPosition' => $table->getHeaderActionsPosition()->name,
            'striped' => $table->isStriped(),
            'deferLoading' => $table->isLoadingDeferred(),
            'groupingSettingsInDropdownOnDesktop' => $table->areGroupingSettingsInDropdownOnDesktop(),
            'groupingSettingsHidden' => $table->areGroupingSettingsHidden(),
            'groupsOnly' => $table->isGroupsOnly(),
            'paginated' => $table->isPaginated(),
            'paginatedWhileReordering' => $table->isPaginatedWhileReordering(),
            'extremePaginationLinks' => $table->hasExtremePaginationLinks(),
            'persistSearchInSession' => $table->persistsSearchInSession(),
            'persistColumnSearchesInSession' => $table->persistsColumnSearchesInSession(),
            'searchable' => $table->isSearchable(),
            'searchOnBlur' => $table->isSearchOnBlur(),
            'persistSortInSession' => $table->persistsSortInSession(),
            'selectCurrentPageOnly' => $table->selectsCurrentPageOnly(),
            'selectable' => $table->isSelectionEnabled(),
            'hiddenFilterIndicators' => false,
            'deselectAllRecordsWhenFiltered' => $table->shouldDeselectAllRecordsWhenFiltered(),
            'persistFiltersInSession' => $table->persistsFiltersInSession(),
        ];
    }

    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('defaultPaginationPageOption')
                ->preload()
                ->searchable()
                ->options(static::combineArray(static::$paginationPageOptions)),

            Forms\Components\Select::make('paginationPageOptions')
                ->preload()
                ->searchable()
                ->multiple()
                ->options(static::combineArray(static::$paginationPageOptions)),

            Forms\Components\TextInput::make('poll')
                ->minValue(1)
                ->suffix('s'),

            Forms\Components\TextInput::make('searchDebounce')
                ->suffix('ms')
                ->integer()
                ->minValue(100),

            Forms\Components\TextInput::make('searchPlaceholder')
                ->maxLength(255),

            Forms\Components\Select::make('defaultSortDirection')
                ->options(static::combineArray(['asc', 'desc'])),

            Forms\Components\Select::make('columnToggleFormColumns')
                ->preload()
                ->searchable()
                ->options(static::combineArray([1, 2, 3, 4])),

            Forms\Components\TextInput::make('columnToggleFormMaxHeight')
                ->suffix('px')
                ->numeric()
                ->minValue(50),

            Forms\Components\Select::make('columnToggleFormWidth')
                ->preload()
                ->searchable()
                ->options(get_enum_options(MaxWidth::class)),

            Forms\Components\TextInput::make('actionsColumnLabel')
                ->maxLength(255),

            Forms\Components\Select::make('actionsAlignment')
                ->preload()
                ->searchable()
                ->options(get_enum_options(Alignment::class)),

            Forms\Components\Select::make('actionsPosition')
                ->preload()
                ->searchable()
                ->options(get_enum_options(ActionsPosition::class)),

            Forms\Components\Select::make('recordCheckboxPosition')
                ->preload()
                ->searchable()
                ->options(get_enum_options(RecordCheckboxPosition::class)),

            Forms\Components\TextInput::make('emptyStateDescription')
                ->maxLength(255),

            Forms\Components\TextInput::make('emptyStateHeading')
                ->maxLength(255),

            // Forms\Components\TextInput::make('emptyStateIcon'), // TODO: add icon plugin

            Forms\Components\Select::make('filtersFormColumns')
                ->preload()
                ->searchable()
                ->options(static::combineArray([1, 2, 3, 4])),

            Forms\Components\TextInput::make('filtersFormMaxHeight')
                ->suffix('px')
                ->numeric()
                ->minValue(50),

            Forms\Components\Select::make('filtersFormWidth')
                ->preload()
                ->searchable()
                ->options(get_enum_options(MaxWidth::class)),

            Forms\Components\Select::make('filtersLayout')
                ->preload()
                ->searchable()
                ->options(get_enum_options(FiltersLayout::class)),

            Forms\Components\TextInput::make('description')
                ->maxLength(255),

            Forms\Components\TextInput::make('heading')
                ->maxLength(255),

            Forms\Components\Select::make('headerActionsPosition')
                ->preload()
                ->searchable()
                ->options(get_enum_options(HeaderActionsPosition::class)),

            Forms\Components\Fieldset::make('Options')
                ->schema(static::options()),
        ];
    }

    protected static function options(): array
    {
        return [
            Forms\Components\Toggle::make('striped'),

            Forms\Components\Toggle::make('deferLoading'),

            Forms\Components\Toggle::make('groupingSettingsInDropdownOnDesktop'),

            Forms\Components\Toggle::make('groupingSettingsHidden'),

            Forms\Components\Toggle::make('groupsOnly')
                ->disabled(), // BUG: lazy loading issue

            Forms\Components\Toggle::make('paginated'),

            Forms\Components\Toggle::make('paginatedWhileReordering'),

            Forms\Components\Toggle::make('extremePaginationLinks'),

            Forms\Components\Toggle::make('persistSearchInSession'),

            Forms\Components\Toggle::make('persistColumnSearchesInSession'),

            Forms\Components\Toggle::make('searchable'),

            Forms\Components\Toggle::make('searchOnBlur'),

            Forms\Components\Toggle::make('persistSortInSession'),

            Forms\Components\Toggle::make('selectCurrentPageOnly'),

            Forms\Components\Toggle::make('selectable'),

            Forms\Components\Toggle::make('hiddenFilterIndicators'),

            Forms\Components\Toggle::make('deselectAllRecordsWhenFiltered'),

            Forms\Components\Toggle::make('persistFiltersInSession'),
        ];
    }

    public static function configureTable(Table $table): void
    {
        $data = AuthPreferences::get();

        foreach ($data as $method => $value) {
            match ($method) {
                'defaultPaginationPageOption' => $table->defaultPaginationPageOption($value),
                'paginationPageOptions' => $table->paginationPageOptions($value),
                'poll' => $table->poll($value),
                'searchDebounce' => $table->searchDebounce($value),
                'searchPlaceholder' => $table->searchPlaceholder($value),
                'defaultSortDirection' => $table->defaultSort(null, $value),
                'columnToggleFormColumns' => $table->columnToggleFormColumns($value),
                'columnToggleFormMaxHeight' => $table->columnToggleFormMaxHeight($value.'px'),
                'columnToggleFormWidth' => $table->columnToggleFormWidth($value),
                'actionsColumnLabel' => $table->actionsColumnLabel($value),
                'actionsAlignment' => $table->actionsAlignment($value),
                'actionsPosition' => $table->actionsPosition(ActionsPosition::{$value}),
                'recordCheckboxPosition' => $table->recordCheckboxPosition(RecordCheckboxPosition::{$value}),
                'emptyStateDescription' => $table->emptyStateDescription($value),
                'emptyStateHeading' => $table->emptyStateHeading($value),
                'emptyStateIcon' => $table->emptyStateIcon($value),
                'filtersFormColumns' => $table->filtersFormColumns($value),
                'filtersFormMaxHeight' => $table->filtersFormMaxHeight($value.'px'),
                'filtersFormWidth' => $table->filtersFormWidth($value),
                'filtersLayout' => $table->filtersLayout(FiltersLayout::{$value}),
                'description' => $table->description($value),
                'heading' => $table->heading($value),
                'headerActionsPosition' => $table->headerActionsPosition(HeaderActionsPosition::{$value}),
                'striped' => $table->striped($value),
                'deferLoading' => $table->deferLoading($value),
                'groupingSettingsInDropdownOnDesktop' => $table->groupingSettingsInDropdownOnDesktop($value),
                'groupingSettingsHidden' => $table->groupingSettingsHidden($value),
                'groupsOnly' => $table->groupsOnly($value),
                'paginated' => $table->paginated($value),
                'paginatedWhileReordering' => $table->paginatedWhileReordering($value),
                'extremePaginationLinks' => $table->extremePaginationLinks($value),
                'persistSearchInSession' => $table->persistSearchInSession($value),
                'persistColumnSearchesInSession' => $table->persistColumnSearchesInSession($value),
                'searchable' => $table->searchable($value),
                'searchOnBlur' => $table->searchOnBlur($value),
                'persistSortInSession' => $table->persistSortInSession($value),
                'selectCurrentPageOnly' => $table->selectCurrentPageOnly($value),
                'selectable' => $table->selectable($value),
                'hiddenFilterIndicators' => $table->hiddenFilterIndicators($value),
                'deselectAllRecordsWhenFiltered' => $table->deselectAllRecordsWhenFiltered($value),
                'persistFiltersInSession' => $table->persistFiltersInSession($value),
                default => null
            };
        }
    }

    protected static function combineArray(array $array): array
    {
        return array_combine($array, $array);
    }

    public static function apply(array $data): void
    {
        //
    }
}
