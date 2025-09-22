<?php

namespace App\Filament\Resources\Productions\Pages;

use App\Filament\Resources\Productions\ProductionResource;
use App\Models\Production;
use Filament\Resources\Pages\CreateRecord;

class CreateProduction extends CreateRecord
{
    protected static string $resource = ProductionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $record = Production::latest('id')->first();

        $prefix = "MR-SKP-";

        if ($record === null or $record === "") {
            $requestNo = $prefix . date('ym') . '-001';
        } else {
            $expNum = explode('-', $record->code);
            if (date('ym') === $expNum[2]) {
            $number = ($expNum[3] + 1);
            $requestNo = $prefix . date('ym') . '-' . str_pad($number, 3, 0, STR_PAD_LEFT);
            } else {
            $requestNo = $prefix . date('ym') . '-001';
            }
        }

        $data['sr_no'] = $requestNo;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
