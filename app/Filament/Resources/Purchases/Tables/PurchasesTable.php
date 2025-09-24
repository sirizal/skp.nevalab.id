<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('No PO')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vendor.name')
                    ->label('Pemasok')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->label('Tgl PO')
                    ->date()
                    ->sortable(),
                TextColumn::make('po_type')
                    ->label('Tipe PO')
                    ->searchable(),
                TextColumn::make('purchase_items_sum_purchase_amount')
                    ->sum('purchaseItems','purchase_amount')
                    ->label('Nilai PO')
                    ->numeric()
                    ->summarize([
                        Sum::make()
                    ])
                    ->sortable(),
                TextColumn::make('purchase_items_sum_receive_amount')
                    ->sum('purchaseItems','receive_amount')
                    ->label('Nilai Penerimaan')
                    ->wrapHeader()
                    ->numeric()
                    ->summarize([
                        Sum::make()
                    ])
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
            ])->defaultSort('id','desc')
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
