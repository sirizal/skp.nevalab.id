<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Vendor')
                    ->required(),
                TextInput::make('address1')->label('Alamat 1'),
                TextInput::make('address2')->label('Alamat 2'),
                TextInput::make('address3')->label('Alamat 3'),
                TextInput::make('village')->label('Desa'),
                TextInput::make('district')->label('Kecamatan'),
                TextInput::make('sub_district')->label('Kabupaten/Kota'),
                TextInput::make('province')->label('Propinsi'),
                TextInput::make('postal_code')->label('Kode Pos'),
                TextInput::make('contact_person')->label('PIC'),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email(),
                TextInput::make('contact_no')->label('No Kontak'),
                TextInput::make('sj_prefix')
                    ->label('Prefix SJ'),
                TextInput::make('inv_prefix')
                    ->label('Prefix Invoice'),
                TextInput::make('bank_name')
                    ->label('Nama Bank'),
                TextInput::make('account_no')
                    ->label('No Rekening'),
                TextInput::make('account_holder_name')
                    ->label('Nama Pemegang Rekening'),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->disk('vendor_invoices')
                    ->image()
                    ->nullable(),
            ]);
    }
}
