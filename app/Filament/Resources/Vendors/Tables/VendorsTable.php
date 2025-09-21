<?php

namespace App\Filament\Resources\Vendors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Vendor')
                    ->searchable(),
                TextColumn::make('address1')
                    ->searchable(),
                TextColumn::make('address2')
                    ->searchable(),
                TextColumn::make('address3')
                    ->searchable(),
                TextColumn::make('village')
                    ->label('Desa')
                    ->searchable(),
                TextColumn::make('district')
                    ->label('Kecamatan')
                    ->searchable(),
                TextColumn::make('sub_district')
                    ->label('Kabupaten/Kota')
                    ->searchable(),
                TextColumn::make('province')
                    ->label('Propinsi')
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label('Kode Pos')
                    ->searchable(),
                TextColumn::make('contact_person')
                    ->label('Nama Kontak')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('contact_no')
                    ->label('No Kontak')
                    ->searchable(),
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
