<?php

namespace App\Filament\Resources\Productions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

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
                    ->summarize([
                        Sum::make()
                    ])
                    ->sortable(),
                TextColumn::make('menu_portions_sum_total_budget_cost')
                    ->sum('menuPortions', 'total_budget_cost')
                    ->label('Total Biaya Anggaran')
                    ->wrapHeader()
                    ->numeric()
                    ->summarize([
                        Sum::make()
                    ])
                    ->sortable(),
                TextColumn::make('material_requests_sum_total_estimated_cost')
                    ->label('Total Biaya Perkiraan')
                    ->sum('materialRequests', 'total_estimated_cost')
                    ->wrapHeader()
                    ->numeric()
                    ->summarize([
                        Sum::make()
                    ])
                    ->sortable(),
                TextColumn::make('material_requests_sum_total_actual_cost')
                    ->label('Total Biaya Aktual')
                    ->sum('materialRequests', 'total_actual_cost')
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
                    ExportBulkAction::make()->exports([
                        ExcelExport::make()->fromTable()
                    ])
                    /* ExportBulkAction::make()->exports([
                        ExcelExport::make()->withColumns([
                            Column::make('Kode Produksi'),
                            Column::make('Tgl Produksi'),
                            Column::make('Total Porsi'),
                            Column::make('Total Biaya Anggaran'),
                            Column::make('Total Biaya Perkiraan'),
                            Column::make('Total Biaya Aktual')
                        ])
                    ]) */
                ]),
            ]);
    }
}
