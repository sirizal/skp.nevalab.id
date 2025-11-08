<?php

namespace App\Filament\Exports;

use App\Models\PurchaseItem;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PurchaseItemExporter extends Exporter
{
    protected static ?string $model = PurchaseItem::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('purchase.purchase_date')
                ->label('Tgl PO'),
            ExportColumn::make('purchase.po_type')
                ->label('Tipe PO'),
            ExportColumn::make('purchase.code')
                ->label('No PO'),
            ExportColumn::make('item.category.name')
                ->label('Kategori Item'),
            ExportColumn::make('item.code')
                ->label('Kode Item'),
            ExportColumn::make('item.name')
                ->label('Deskripsi'),
            ExportColumn::make('purchase_qty')
                ->label('Qty PO'),
            ExportColumn::make('uom.code')
                ->label('Satuan'),
            ExportColumn::make('purchase_price')
                ->label('Harga'),
            ExportColumn::make('purchase_amount')
                ->label('Nilai PO')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your purchase item export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
