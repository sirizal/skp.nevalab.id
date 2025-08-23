<?php

namespace App\Filament\Resources\Candidates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CandidateForm
{   
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email(),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel(),
                Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('resume_file')
                    ->label('File CV'),
                TextInput::make('identification_number')
                    ->label('Nomor KTP')
                    ->required(),
                FileUpload::make('identification_file')
                    ->label('File KTP'),
                TextInput::make('place_of_birth')
                    ->label('Tempat Lahir'),
                DatePicker::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->options([
                        'Melamar' => 'Melamar',
                        'Diinterview' => 'Diinterview',
                        'Diterima Kerja' => 'Diterima Kerja',
                        'Tidak Diterima Kerja' => 'Tidak Diterima Kerja',
                    ]),
                TextInput::make('position_applied')
                    ->label('Posisi yang Dilamar')
                    ->required(),
                TextInput::make('health_status')
                    ->label('Status Kesehatan'),
                Select::make('marital_status')
                    ->label('Status Pernikahan')
                    ->options([
                        'Belum Menikah' => 'Belum Menikah',
                        'Menikah' => 'Menikah',
                        'Cerai' => 'Cerai',
                    ]),
                TextInput::make('illness_history')
                    ->label('Riwayat Penyakit'),
                TextInput::make('ability_work_shift')
                    ->label('Kemampuan Bekerja Shift'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
                TextInput::make('education_level')
                    ->label('Tingkat Pendidikan'),
                TextInput::make('skills')
                    ->label('Keahlian')
                    ->columnSpanFull(),
                DatePicker::make('application_date')
                    ->label('Tanggal Pengajuan Lamaran')
                    ->required(),
                DatePicker::make('interview_date')
                    ->label('Tanggal Interview'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required()
            ]);
    }
}
