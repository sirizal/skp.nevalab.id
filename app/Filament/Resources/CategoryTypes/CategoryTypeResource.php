<?php

namespace App\Filament\Resources\CategoryTypes;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\CategoryType;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryTypes\Pages\EditCategoryType;
use App\Filament\Resources\CategoryTypes\Pages\ListCategoryTypes;
use App\Filament\Resources\CategoryTypes\Pages\CreateCategoryType;
use App\Filament\Resources\CategoryTypes\Schemas\CategoryTypeForm;
use App\Filament\Resources\CategoryTypes\Tables\CategoryTypesTable;
use App\Filament\Resources\CategoryTypes\RelationManagers\CategoriesRelationManager;

class CategoryTypeResource extends Resource
{
    protected static ?string $model = CategoryType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $pluralLabel = 'Kategori';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return CategoryTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryTypes::route('/'),
            'create' => CreateCategoryType::route('/create'),
            'edit' => EditCategoryType::route('/{record}/edit'),
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
