<?php

namespace App\Filament\Resources\Receives;

use App\Filament\Resources\Receives\Pages\CreateReceive;
use App\Filament\Resources\Receives\Pages\EditReceive;
use App\Filament\Resources\Receives\Pages\ListReceives;
use App\Filament\Resources\Receives\Schemas\ReceiveForm;
use App\Filament\Resources\Receives\Tables\ReceivesTable;
use App\Models\Receive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiveResource extends Resource
{
    protected static ?string $model = Receive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ReceiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceivesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceives::route('/'),
            'create' => CreateReceive::route('/create'),
            'edit' => EditReceive::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
