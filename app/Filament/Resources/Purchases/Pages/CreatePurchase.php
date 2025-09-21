<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Purchase;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $record = Purchase::latest('id')->first();

        $prefix = "PO-SKP-";

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

        $data['code'] = $requestNo;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
