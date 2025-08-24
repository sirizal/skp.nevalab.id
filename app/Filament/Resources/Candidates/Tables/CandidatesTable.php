<?php

namespace App\Filament\Resources\Candidates\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Exports\CandidateExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ForceDeleteBulkAction;

class CandidatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable(),
                TextColumn::make('identification_number')
                    ->label('Nomor KTP')
                    ->searchable(),
                TextColumn::make('position_applied')
                    ->label('Posisi Dilamar')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
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
                    ->exporter(CandidateExporter::class)
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
