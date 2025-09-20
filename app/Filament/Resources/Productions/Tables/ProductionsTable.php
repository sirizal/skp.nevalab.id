<?php

namespace App\Filament\Resources\Productions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kode Produksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('production_date')
                    ->label('Tanggal Produksi')
                    ->wrapHeader()
                    ->date()
                    ->sortable(),
                TextColumn::make('menu_portions_sum_portion_count')
                    ->sum('menuPortions','portion_count')
                    ->label('Total Porsi')
                    ->wrapHeader()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('menu_portions_sum_total_budget_cost')
                    ->sum('menuPortions', 'total_budget_cost')
                    ->label('Total Biaya Anggaran')
                    ->wrapHeader()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('material_requests_sum_total_estimated_cost')
                    ->label('Total Biaya Perkiraan')
                    ->sum('materialRequests', 'total_estimated_cost')
                    ->wrapHeader()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('material_requests_sum_total_actual_cost')
                    ->label('Total Biaya Aktual')
                    ->sum('materialRequests', 'total_actual_cost')
                    ->wrapHeader()
                    ->numeric()
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
            ])->defaultSort('id', 'desc')
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
