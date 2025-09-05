<?php

namespace App\Filament\Resources\CategoryTypes\Pages;

use App\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryType extends EditRecord
{
    protected static string $resource = CategoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
