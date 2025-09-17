<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship(
                        'category', 
                        'name',
                        fn ($query) => $query->where('category_type_id', 2))
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Resep')
                    ->required(),
            ]);
    }
}
