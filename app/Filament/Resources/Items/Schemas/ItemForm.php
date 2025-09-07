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
                Select::make('category_id')
                    ->relationship(
                        'category', 
                        'name',
                        fn ($query) => $query->where('category_type_id', 1))
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->label('Kategori')
                    ->required(),
                TextInput::make('code')
                    ->label('Kode Barang')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->visible(fn ($operation) => $operation === 'edit'), 
                TextInput::make('name')
                    ->label('Nama Barang')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                TextInput::make('standard_price')
                    ->label('Harga Standar')
                    ->required()
                    ->numeric(),
                TextInput::make('packing_unit')
                    ->label('Kemasan'),
                Select::make('uom_id')
                    ->relationship('uom', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required(),
                FileUpload::make('image_path')
                    ->label('Gambar')
                    ->image(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Toggle::make('is_stock_item')
                    ->default(true)
                    ->required(),
                TextInput::make('barcode'),
            ]);
    }
}
