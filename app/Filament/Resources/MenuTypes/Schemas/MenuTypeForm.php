<?php

namespace App\Filament\Resources\MenuTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MenuTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tipe Menu')
                    ->required(),
                TextInput::make('budget_cost')
                    ->label('Biaya Anggaran')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
