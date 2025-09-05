<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Items\ItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }   

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Automatically generate a unique code for the item
        $category = \App\Models\Category::find($data['category_id']);
        if ($category) {
            $prefix = $category->itemcode ?? 'ITEM';
            $sequentialNumber = str_pad($category->sequence + 1, 3, '0', STR_PAD_LEFT);
            $data['code'] = $prefix . $sequentialNumber;
            // Update the sequence in the category
            $category->update(['sequence' => $category->sequence + 1]);
        } else {            
            $data['code'] = 'ITEM-' . strtoupper(uniqid());
        }
        return $data;
    }
}
