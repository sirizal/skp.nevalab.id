<?php

namespace App\Filament\Exports;

use App\Models\Candidate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class CandidateExporter extends Exporter
{
    protected static ?string $model = Candidate::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Nama Lengkap'),
            ExportColumn::make('gender')
                ->label('Jenis Kelamin'),
            /* ExportColumn::make('email'),
            ExportColumn::make('phone'), */
            ExportColumn::make('address')
                ->label('Alamat'),
            // ExportColumn::make('resume_file'),
            ExportColumn::make('identification_number')
                ->label('Nomor KTP'),
            //ExportColumn::make('identification_file'),
            ExportColumn::make('place_of_birth')
                ->label('Tempat Lahir'),
            /* ExportColumn::make('date_of_birth')
                ->label('Tanggal Lahir')
                ->formatStateUsing(fn ($state) => Carbon::hasFormat($state, 'Y-m-d H:i:s') ? Carbon::parse($state)->format('Y-m-d') : 'Invalid Date' ), */
            ExportColumn::make('DOB')
                ->label('Tanggal Lahir')
                ,
            ExportColumn::make('age')
                ->label('Usia')
                ->state(function(Candidate $record): float {
                    return floatval($record->age);
                }),
            //ExportColumn::make('status'),
            ExportColumn::make('position_applied')
                ->label('Posisi Dilamar'),
            ExportColumn::make('health_status')
                ->label('Status Kesehatan'),
            ExportColumn::make('marital_status')
                ->label('Status Perkawinan'),
            ExportColumn::make('illness_history')
                ->label('Riwayat Penyakit'),
            ExportColumn::make('ability_work_shift')
                ->label('Kemampuan Shift Kerja'),
            ExportColumn::make('notes')
                ->label('Catatan'),
            /* ExportColumn::make('education_level'),
            ExportColumn::make('skills'),
            ExportColumn::make('application_date'),
            ExportColumn::make('interview_date'),
            ExportColumn::make('is_active'),
            ExportColumn::make('user_id'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'), */
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your candidate export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
