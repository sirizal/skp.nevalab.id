<?php

namespace App\Filament\Resources\MenuTypes\Pages;

use App\Filament\Resources\MenuTypes\MenuTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuType extends EditRecord
{
    protected static string $resource = MenuTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
