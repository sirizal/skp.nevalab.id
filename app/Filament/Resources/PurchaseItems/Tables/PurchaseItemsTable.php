<?php

namespace App\Filament\Resources\PurchaseItems\Tables;

use App\Filament\Exports\PurchaseItemExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PurchaseItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchase.purchase_date')
                    ->label('Tgl PO')
                    ->date()
                    ->sortable(),
                TextColumn::make('purchase.po_type')
                    ->label('Tipe PO'),
                TextColumn::make('purchase.code')
                    ->label('No PO')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.code')
                    ->label('Kode Item')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.name')
                    ->label('Deskripsi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('purchase_qty')
                    ->label('PO Qty'),
                TextColumn::make('uom.code')
                    ->label('Satuan'),
                TextColumn::make('purchase_price')
                    ->label('Harga PO')
                    ->sortable(),
                TextColumn::make('purchase_amount')
                    ->label('Nilai PO')
                    ->sortable(),
                TextColumn::make('item.category.name')
                        ->label('Kategori Item')
                        ->sortable()
                        ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Ekspor ke excel')
                    ->exporter(PurchaseItemExporter::class)
                    ->formats([
                        ExportFormat::Xlsx
                    ])
            ])
            ->toolbarActions([
                /* BulkActionGroup::make([
                    //DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make()->fromTable()
                    ])
                ]), */
            ]);
    }
}
