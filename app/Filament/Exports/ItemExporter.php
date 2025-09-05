<?php

namespace App\Filament\Exports;

use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ItemExporter extends Exporter
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('code'),
            ExportColumn::make('name'),
            ExportColumn::make('description'),
            ExportColumn::make('packing_unit'),
            ExportColumn::make('standard_price'),
            ExportColumn::make('uom.code')
                ->label('UOM'),
            ExportColumn::make('category.name')
                ->label('Category'),
            ExportColumn::make('is_active'),
            ExportColumn::make('is_stock_item'),
            //ExportColumn::make('image_path'),
            //ExportColumn::make('barcode'),
            //ExportColumn::make('deleted_at'),
            //ExportColumn::make('created_at'),
            //ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
