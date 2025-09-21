<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Exports\ItemExporter;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('code')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->label('Nama Barang')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('standard_price')
                    ->label('Harga Standar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('uom.code')
                    ->label('Satuan')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('packing_unit')
                    ->label('Kemasan')
                    ->wrap()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                IconColumn::make('is_stock_item')
                    ->label('Stock')
                    ->boolean(),
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
            ->headerActions([
                ExportAction::make()
                    ->label('Ekspor ke Excel')
                    ->exporter(ItemExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                    ]),
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
