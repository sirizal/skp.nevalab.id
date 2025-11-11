<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Models\Item;
use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater\TableColumn;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('code')
                                    ->label('No PO')
                                    ->required()
                                    ->visible(fn ($operation) => $operation === 'edit'),
                                Select::make('vendor_id')
                                    ->label('Pemasok')
                                    ->relationship('vendor', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                DatePicker::make('purchase_date')
                                    ->label('Tanggal PO')
                                    ->required(),
                                Select::make('po_type')
                                    ->label('Tipe PO')
                                    ->options([
                                        'Bahan Baku' => 'Bahan Baku',
                                        'Operasional' => 'Operasional'
                                    ])
                                    ->reactive()
                                    ->required()
                            ])
                            ->columns(4),
                    ])->columnSpan(4),
                Group::make()
                    ->schema([
                        Section::make()
                        ->schema([
                            Repeater::make('purchaseItems')
                                ->label('Detail Pembelian')
                                ->relationship('purchaseItems')
                                ->compact()
                                ->table([
                                    /* TableColumn::make('Kategori')
                                        ->width('150px'), */
                                    TableColumn::make('Item')
                                        ->width('200px'),
                                    TableColumn::make('Satuan')
                                        ->width('100px'),
                                    TableColumn::make('Harga')
                                        ->width('100px'),
                                    TableColumn::make('Qty')
                                        ->width('100px'),
                                    TableColumn::make('Total')
                                        ->width('150px'),
                                ])
                                ->schema([
                                    /* Select::make('category_id')
                                        ->label('Kategori')
                                        ->searchable()
                                        ->preload()
                                        ->options(function(callable $get) {
                                            $coa = $get('../../po_type');
                                            if($coa) {
                                                return Category::all()->where('coa',$coa)->pluck('name','id');
                                            }
                                            return Category::all()->pluck('name','id');
                                        })
                                        ->dehydrated(false), */
                                    Select::make('item_id')
                                        ->label('Item')
                                        ->searchable()
                                        ->preload()
                                        ->options(function () {
                                            /* $categoryId = $get('category_id');
                                            if ($categoryId) {
                                                return \App\Models\Item::where('category_id', $categoryId)->get()->pluck('name', 'id');
                                            } */
                                            return \App\Models\Item::where('is_active',true)->pluck('name', 'id');
                                        })
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, $state) {
                                            $item = \App\Models\Item::find($state);
                                            if ($item) {
                                                $set('uom_id', $item->uom_id);
                                                $set('purchase_price', $item->standard_price);
                                            } else {
                                                $set('uom_id', null);
                                                $set('purchase_price', 0);
                                            }
                                        })
                                        ->required(),
                                        //->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                    Select::make('uom_id')
                                        ->label('Satuan')
                                        ->options(\App\Models\Uom::all()->pluck('code', 'id'))
                                        ->required(),
                                    TextInput::make('purchase_price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                $purchaseQty = $get('purchase_qty') ?? 0;
                                                $totalEstimatedCost = $purchaseQty * $state;
                                                $set('purchase_amount', $totalEstimatedCost);
                                            }),
                                    TextInput::make('purchase_qty')
                                        ->label('Qty Diminta')
                                        ->numeric()
                                        ->live(onBlur:true)
                                        ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                            $standardPrice = $get('purchase_price') ?? 0;
                                            $totalEstimatedCost = $standardPrice * $state;
                                            $set('purchase_amount', $totalEstimatedCost);
                                        })
                                        ->required()
                                        ->default(0),
                                    TextInput::make('purchase_amount')
                                        ->label('Total')
                                        ->numeric()
                                        ->required()
                                        ->default(0)
                                        ->readOnly(),
                                ])
                                ->defaultItems(1)
                        ])
                    ])
                    ->columnSpan(4),
            ])
            ->columns(4);
    }
}
