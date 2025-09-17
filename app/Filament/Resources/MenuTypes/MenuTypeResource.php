<?php

namespace App\Filament\Resources\MenuTypes;

use App\Filament\Resources\MenuTypes\Pages\CreateMenuType;
use App\Filament\Resources\MenuTypes\Pages\EditMenuType;
use App\Filament\Resources\MenuTypes\Pages\ListMenuTypes;
use App\Filament\Resources\MenuTypes\Schemas\MenuTypeForm;
use App\Filament\Resources\MenuTypes\Tables\MenuTypesTable;
use App\Models\MenuType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuTypeResource extends Resource
{
    protected static ?string $model = MenuType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MenuTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuTypesTable::configure($table);
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
            'index' => ListMenuTypes::route('/'),
            'create' => CreateMenuType::route('/create'),
            'edit' => EditMenuType::route('/{record}/edit'),
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
