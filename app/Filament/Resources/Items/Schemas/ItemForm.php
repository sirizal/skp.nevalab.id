<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('standard_price')
                    ->required()
                    ->numeric(),
                TextInput::make('packing_unit'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_stock_item')
                    ->required(),
                Select::make('uom_id')
                    ->relationship('uom', 'name')
                    ->searchable()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required(),
                FileUpload::make('image_path')
                    ->image(),
                TextInput::make('barcode'),
            ]);
    }
}
