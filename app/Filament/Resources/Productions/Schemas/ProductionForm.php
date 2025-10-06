<?php

namespace App\Filament\Resources\Productions\Schemas;

use App\Models\Production;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Repeater\TableColumn;

class ProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(static::getProductionInfo())
                            ->columns(2),
                        Section::make()
                            ->schema([
                                static::getMenuPlanRepeater()
                            ]),
                    ])
                    ->columnSpan(2),
                Section::make()
                    ->schema([
                        TextEntry::make('total_budget_cost')
                            ->label('Budget Biaya Produksi')
                            ->state(fn (?Production $record) => $record ? 'Rp ' . number_format($record->menuPortions->sum('total_budget_cost'), 0, ',', '.') : 'Rp 0'),
                        TextEntry::make('total_estimated_cost')
                            ->label('Estimasi Biaya Produksi')
                            ->state(fn (?Production $record) => $record ? 'Rp ' . number_format($record->materialRequests->sum('total_estimated_cost'), 0, ',', '.') : 'Rp 0')
                            ->color(function (?Production $record) {
                                if ($record) {
                                    $estimated = $record->materialRequests->sum('total_estimated_cost');
                                    $budget = $record->menuPortions->sum('total_budget_cost');
                                    if ($estimated > $budget) {
                                        return 'danger';
                                    } elseif ($estimated == $budget) {
                                        return 'warning';
                                    } else {
                                        return 'success';
                                    }
                                }
                                return null;
                            }),
                        TextEntry::make('total_actual_cost')
                            ->label('Realisasi Biaya Produksi')
                            ->state(fn (?Production $record) => $record ? 'Rp ' . number_format($record->materialRequests->sum('total_actual_cost'), 0, ',', '.') : 'Rp 0')
                            ->color(function (?Production $record) {
                                if ($record) {
                                    $actual = $record->materialRequests->sum('total_actual_cost');
                                    $budget = $record->menuPortions->sum('total_budget_cost');
                                    if ($actual > $budget) {
                                        return 'danger';
                                    } elseif ($actual == $budget) {
                                        return 'warning';
                                    } else {
                                        return 'success';
                                    }
                                }
                                return null;
                            }),
                    ]),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                static::getMenuPortionRepeater()
                            ])
                    ])
                    ->columnSpan(3),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                static::getMaterialRequestRepeater()
                            ])
                    ])
                    ->columnSpan(3),
                
            ])
            ->columns(3);
    }

    public static function getProductionInfo(): array
    {
        return [
            TextInput::make('name')
                ->label('Nama Produksi')
                ->required()
                ->maxLength(255),
            DatePicker::make('production_date')
                ->label('Tanggal Produksi')
                ->required(),
        ];
    }

    public static function getMenuPlanRepeater(): Repeater
    {
        return Repeater::make('menuPlans')
            ->relationship('menuPlans')
            ->label('Rencana Menu')
            ->table([
                TableColumn::make('Kategori')
                    ->width('200px'),
                TableColumn::make('Resep')
                    ->width('200px'),
            ])
            ->schema([
                Select::make('category_id')
                    ->label('Kategori')
                    ->options(\App\Models\Category::all()->where('category_type_id',2)->pluck('name', 'id'))
                    ->required(),
                Select::make('recipe_id')
                    ->label('Resep')
                    ->searchable()
                    ->preload()
                    ->options(function (callable $get) {
                        $categoryId = $get('category_id');
                        if ($categoryId) {
                            return \App\Models\Recipe::where('category_id', $categoryId)->get()->pluck('name', 'id');
                        }
                        return \App\Models\Recipe::all()->pluck('name', 'id');
                    })
                    ->required(),
            ])
            ->columns(2)
            ->defaultItems(1)
            ->minItems(1)
            ->maxItems(10);
    }

    public static function getMenuPortionRepeater(): Repeater
    {
        return Repeater::make('menuPortions')
            ->relationship('menuPortions')
            ->label('Rencana Porsi Menu')
            ->table([
                TableColumn::make('Tipe Menu')
                    ->width('200px'),
                TableColumn::make('Budget')
                    ->width('150px'),
                TableColumn::make('Jumlah Porsi')
                    ->width('150px'),
                TableColumn::make('Total Biaya Anggaran')
                    ->width('200px'),
            ])
            ->schema([
                Select::make('menu_type_id')
                    ->label('Tipe Menu')
                    ->options(\App\Models\MenuType::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $menuType = \App\Models\MenuType::find($state);
                        if ($menuType) {
                            $set('budget_cost', $menuType->budget_cost);
                        } else {
                            $set('budget_cost', 0);
                        }
                    })
                    ->required(),
                TextInput::make('budget_cost')
                    ->label('Budget')
                    ->numeric()
                    ->required()
                    ->default(0),
                TextInput::make('portion_count')
                    ->label('Jumlah Porsi')
                    ->numeric()
                    ->required()
                    ->live(debounce:500)
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        $budgetCost = $get('budget_cost') ?? 0;
                        $totalBudgetCost = $budgetCost * $state;
                        $set('total_budget_cost', $totalBudgetCost);
                    })
                    ->debounce(500)
                    ->default(0),
                TextInput::make('total_budget_cost')
                    ->label('Total Biaya Anggaran')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->readOnly(),
            ])
            ->defaultItems(1)
            ->minItems(1)
            ->maxItems(10);
    }
    
    public static function getMaterialRequestRepeater(): Repeater
    {
        return Repeater::make('materialRequests')
            ->relationship('materialRequests')
            ->label('Permintaan Bahan')
            ->table([
                TableColumn::make('Bahan')
                    ->width('200px'),
                TableColumn::make('Satuan')
                    ->width('150px'),
                TableColumn::make('Harga Standar')
                    ->width('150px'),
                TableColumn::make('Qty Diminta')
                    ->width('150px')
                    ->wrapHeader(),
                TableColumn::make('Perkiraan Biaya')
                    ->width('150px')
                    ->wrapHeader(),
            ])
            ->schema([
                Select::make('item_id')
                    ->label('Bahan')
                    ->options(\App\Models\Item::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $item = \App\Models\Item::find($state);
                        if ($item) {
                            $set('uom_id', $item->uom_id);
                            $set('standard_price', $item->standard_price);
                        } else {
                            $set('uom_id', null);
                            $set('standard_price', 0);
                        }
                    })
                    ->required(),
                Select::make('uom_id')
                    ->label('Satuan')
                    ->options(\App\Models\Uom::all()->pluck('code', 'id'))
                    ->required(),
                TextInput::make('standard_price')
                    ->label('Harga Standar')
                    ->numeric()
                    ->live(debounce:500)
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        $requestQty = $get('request_quantity') ?? 0;
                        $totalEstimatedCost = $requestQty * $state;
                        $set('total_estimated_cost', $totalEstimatedCost);
                    })
                    ->required()
                    ->default(0),
                TextInput::make('request_quantity')
                    ->label('Qty Diminta')
                    ->numeric()
                    ->live(debounce:500)
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        $standardPrice = $get('standard_price') ?? 0;
                        $totalEstimatedCost = $standardPrice * $state;
                        $set('total_estimated_cost', $totalEstimatedCost);
                    })
                    ->required()
                    ->default(0),/* 
                TextInput::make('used_quantity')
                    ->label('Qty Digunakan')
                    ->numeric()
                    ->required()
                    ->lte('request_quantity')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        $standardPrice = $get('standard_price') ?? 0;
                        $returnedQuantity = $get('returned_quantity') ?? 0;
                        $totalActualCost = $standardPrice * ($state-$returnedQuantity);
                        $set('total_actual_cost', $totalActualCost);
                    })
                    ->debounce(500)
                    ->default(0),
                TextInput::make('returned_quantity')
                    ->label('Qty Dikembalikan')
                    ->numeric()
                    ->required()
                    ->lte('used_quantity')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        $standardPrice = $get('standard_price') ?? 0;
                        $usedQuantity = $get('used_quantity') ?? 0;
                        $totalActualCost = $standardPrice * ($usedQuantity-$state);
                        $set('total_actual_cost', $totalActualCost);
                    })
                    ->debounce(500)
                    ->default(0), */
                TextInput::make('total_estimated_cost')
                    ->readOnly()
                    ->default(0),/* 
                Hidden::make('total_actual_cost')
                    ->default(0), */
            ])
            ->defaultItems(1)
            ->minItems(1)
            ->maxItems(50);
    }
}
