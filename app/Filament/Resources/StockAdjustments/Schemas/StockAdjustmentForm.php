<?php

namespace App\Filament\Resources\StockAdjustments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('adjustment_date')
                    ->label('Tanggal Adjustment')
                    ->default(now())
                    ->required(),
                Select::make('adjustment_type')
                    ->label('Tipe Adjustment')
                    ->options([
                        'plus' => 'Penambahan',
                        'minus' => 'Pengurangan',
                    ])
                    ->required()
                    ->live(),
                Select::make('item_id')
                    ->label('Material')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $item = \App\Models\Item::find($state);
                        if ($item) {
                            $set('uom_id', $item->uom_id);
                            $set('adjustment_price', $item->standard_price);
                        } else {
                            $set('uom_id', null);
                            $set('adjustment_price', 0);
                        }
                    }),
                Select::make('uom_id')
                    ->label('Satuan')
                    ->relationship('uom', 'code')
                    ->required(),
                TextInput::make('adjustment_price')
                    ->label('Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('adjustment_qty')
                    ->label('Qty')
                    ->numeric()
                    ->required(),
                TextInput::make('adjustment_reason')
                    ->label('Alasan')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
