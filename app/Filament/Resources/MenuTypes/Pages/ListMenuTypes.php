<?php

namespace App\Filament\Resources\MenuTypes\Pages;

use App\Filament\Resources\MenuTypes\MenuTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuTypes extends ListRecords
{
    protected static string $resource = MenuTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
