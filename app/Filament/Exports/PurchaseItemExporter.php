<?php

namespace App\Filament\Exports;

use App\Models\PurchaseItem;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\DatePicker;
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
            ExportColumn::make('purchase.vendor.name')
                ->label('Pemasok'),
            ExportColumn::make('receiveItems.receive.invoice_no')
                ->label('No Invoice'),
            ExportColumn::make('receiveItems.receive.invoice_date')
                ->label('Tgl Invoice'),
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

    public static function getOptionsFormComponents(): array
    {
        return [
            DatePicker::make('purchase_date_from')
                ->label('Tgl PO Dari'),
            DatePicker::make('purchase_date_until')
                ->label('Tgl PO Sampai'),
        ];
    }

    public function makeXlsxRow(array $values, ?Style $style = null): Row
    {
        $styleMerger = new StyleMerger;

        $numberStyle = (new Style)->setFormat('#,##0.00');
        $dateStyle = (new Style)->setFormat('d/m/yyyy');

        $cells = [];

        foreach (array_keys($this->columnMap) as $columnIndex => $column) {
            $value = $values[$columnIndex];

            $cells[] = match ($column) {
                'purchase.purchase_date', 'receiveItems.receive.invoice_date' => Cell::fromValue(
                    $value instanceof Carbon ? $value : (
                        is_string($value) && strlen($value) > 4 && str_contains($value, '-') ? Carbon::parse($value) : $value
                    ),
                    $style !== null ? $styleMerger->merge($dateStyle, $style) : $dateStyle,
                ),
                'purchase_qty', 'purchase_price', 'purchase_amount', 'item.buying_price' => Cell::fromValue(
                    is_numeric($value) ? (float) $value : $value,
                    $style !== null ? $styleMerger->merge($numberStyle, $style) : $numberStyle,
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
