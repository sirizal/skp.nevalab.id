<?php

namespace App\Filament\Resources\StockMutations\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMutationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.code')
                    ->label('No. Item')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.uom.code')
                    ->label('Satuan')
                    ->sortable(),
                TextColumn::make('receive_qty')
                    ->label('Qty Masuk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('outgoing_qty')
                    ->label('Qty Keluar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance_qty')
                    ->label('Saldo Stok')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
