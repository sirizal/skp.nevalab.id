<?php

namespace App\Filament\Resources\StockAdjustments\Pages;

use App\Filament\Resources\StockAdjustments\StockAdjustmentResource;
use App\Models\StockMutation;
use Filament\Resources\Pages\CreateRecord;

class CreateStockAdjustment extends CreateRecord
{
    protected static string $resource = StockAdjustmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (! isset($data['adjustment_date'])) {
            $data['adjustment_date'] = now();
        }

        if ($data['adjustment_type'] === 'plus') {
            $data['remain_qty'] = $data['adjustment_qty'];
        } else {
            $data['remain_qty'] = 0;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $stockMutation = StockMutation::where('item_id', $this->record->item_id)->first();

        if ($this->record->adjustment_type === 'plus') {
            if ($stockMutation) {
                $stockMutation->receive_qty += $this->record->adjustment_qty;
                $stockMutation->update();
                $stockMutation->setBalanceStock();
            } else {
                $smCreate = StockMutation::create([
                    'item_id' => $this->record->item_id,
                    'receive_qty' => $this->record->adjustment_qty,
                    'outgoing_qty' => 0,
                    'balance_qty' => $this->record->adjustment_qty,
                ]);
                $smCreate->setBalanceStock();
            }
        } else {
            if ($stockMutation) {
                $stockMutation->outgoing_qty += $this->record->adjustment_qty;
                $stockMutation->update();
                $stockMutation->setBalanceStock();
            } else {
                $smCreate = StockMutation::create([
                    'item_id' => $this->record->item_id,
                    'receive_qty' => 0,
                    'outgoing_qty' => $this->record->adjustment_qty,
                    'balance_qty' => -$this->record->adjustment_qty,
                ]);
                $smCreate->setBalanceStock();
            }
        }
    }
}
