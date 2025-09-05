<?php

namespace App\Filament\Resources\CategoryTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
