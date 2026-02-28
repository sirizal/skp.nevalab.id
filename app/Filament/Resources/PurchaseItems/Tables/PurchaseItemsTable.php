<?php

namespace App\Filament\Resources\PurchaseItems\Tables;

use App\Filament\Exports\PurchaseItemExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                \Filament\Tables\Filters\Filter::make('purchase_date')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('purchase_date_from'),
                        \Filament\Forms\Components\DatePicker::make('purchase_date_until'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['purchase_date_from'], fn ($query) => $query->whereHas('purchase', fn ($query) => $query->where('purchase_date', '>=', $data['purchase_date_from'])))
                        ->when($data['purchase_date_until'], fn ($query) => $query->whereHas('purchase', fn ($query) => $query->where('purchase_date', '<=', $data['purchase_date_until'])))
                    ),
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Ekspor ke excel')
                    ->exporter(PurchaseItemExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ]),
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
