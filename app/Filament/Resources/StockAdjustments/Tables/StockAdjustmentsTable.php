<?php

namespace App\Filament\Resources\StockAdjustments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StockAdjustmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('adjustment_date')
                    ->label('Tanggal')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
                TextColumn::make('item.code')
                    ->label('Kode Material')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.name')
                    ->label('Nama Material')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('adjustment_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'plus' => 'success',
                        'minus' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'plus' => 'Penambahan',
                        'minus' => 'Pengurangan',
                    }),
                TextColumn::make('adjustment_qty')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('uom.code')
                    ->label('Satuan')
                    ->sortable(),
                TextColumn::make('adjustment_price')
                    ->label('Harga')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remain_qty')
                    ->label('Sisa Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('adjustment_reason')
                    ->label('Alasan')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
