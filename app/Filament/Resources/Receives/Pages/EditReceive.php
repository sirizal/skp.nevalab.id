<?php

namespace App\Filament\Resources\Receives\Pages;

use App\Filament\Resources\Receives\ReceiveResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditReceive extends EditRecord
{
    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
