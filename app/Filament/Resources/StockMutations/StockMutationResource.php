<?php

namespace App\Filament\Resources\StockMutations;

use App\Filament\Resources\StockMutations\Pages\ListStockMutations;
use App\Filament\Resources\StockMutations\Tables\StockMutationsTable;
use App\Models\StockMutation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StockMutationResource extends Resource
{
    protected static ?string $model = StockMutation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static ?string $recordTitleAttribute = 'item.name';

    protected static ?string $navigationLabel = 'Mutasi Stok';

    protected static ?string $pluralLabel = 'Mutasi Stok';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return StockMutationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockMutations::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery();
    }
}
