<?php

namespace App\Filament\Resources\Productions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('menuPlans')
                    ->relationship('menuPlans')
                    ->table([
                        TableColumn::make('Kategori')
                            ->width('200px'),
                        TableColumn::make('Resep')
                            ->width('200px'),
                    ])
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->options(\App\Models\Category::all()->pluck('name', 'id'))
                            ->required(),
                        Select::make('recipe_id')
                            ->label('Resep')
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
                    ->maxItems(10),
                Flex::make([
                    Section::make('Informasi Produksi')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Produksi')
                                ->required()
                                ->maxLength(255),
                            DatePicker::make('production_date')
                                ->label('Tanggal Produksi')
                                ->required(),
                            TextInput::make('total_budget_cost')
                                ->label('Total Biaya Anggaran')
                                ->numeric()
                                ->default(0)
                                ->disabled(),
                            TextInput::make('total_estimated_cost')
                                ->label('Total Biaya Perkiraan')
                                ->numeric()
                                ->default(0)
                                ->disabled(),
                            TextInput::make('total_actual_cost')
                                ->label('Total Biaya Aktual')
                                ->numeric()
                                ->default(0)
                                ->disabled(),
                            TextInput::make('sr_no')
                                ->label('SR No.')
                                ->maxLength(255),
                        ])->columns(2),
                    Section::make('Rencana Menu')
                        ->schema([
                            Repeater::make('menuPlans')
                                ->relationship('menuPlans')
                                ->schema([
                                    Select::make('category_id')
                                        ->label('Kategori')
                                        ->options(\App\Models\Category::all()->pluck('name', 'id'))
                                        ->required(),
                                    Select::make('recipe_id')
                                        ->label('Resep')
                                        ->options(function (callable $get) {
                                            $categoryId = $get('category_id');
                                            if ($categoryId) {
                                                return \App\Models\Recipe::where('category_id', $categoryId)->get()->pluck('name', 'id');
                                            }
                                            return \App\Models\Recipe::all()->pluck('name', 'id');
                                        })
                                        ->required(),
                                ])
                                ->hiddenLabel(true)
                                ->columns(2),
                        ]),
                    ]),
                
            ]);
    }
}
