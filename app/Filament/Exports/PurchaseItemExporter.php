<?php

namespace App\Filament\Exports;

use App\Models\PurchaseItem;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Manager\Style\StyleMerger;

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
                ->label('Harga Jual'),
            ExportColumn::make('purchase_amount')
                ->label('Nilai PO'),
            ExportColumn::make('item.buying_price')
                ->label('Harga Beli'),
        ];
    }

    public function makeXlsxRow(array $values, ?Style $style = null): Row
    {
        $styleMerger = new StyleMerger;

        $numberStyle = (new Style)->setFormat('#,##0.00');
        $dateStyle = (new Style)->setFormat('yyyy-mm-dd');

        $cells = [];

        foreach (array_keys($this->columnMap) as $columnIndex => $column) {
            $value = $values[$columnIndex];

            $cells[] = match ($column) {
                'purchase.purchase_date' => Cell::fromValue(
                    $value instanceof Carbon ? $value : ($value ? Carbon::parse($value) : null),
                    $styleMerger->merge($dateStyle, $style),
                ),
                'purchase_qty', 'purchase_price', 'purchase_amount', 'item.buying_price' => Cell::fromValue(
                    $value,
                    $styleMerger->merge($numberStyle, $style),
                ),
                default => Cell::fromValue($value),
            };
        }

        return new Row($cells, $style);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your purchase item export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
