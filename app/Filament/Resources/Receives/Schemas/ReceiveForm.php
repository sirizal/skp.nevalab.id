<?php

namespace App\Filament\Resources\Receives\Schemas;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Symfony\Component\Clock\DatePoint;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\TextEntry;

class ReceiveForm
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
                                    ->label('No Penerimaan'),
                                Select::make('vendor_id')
                                    ->label('Pemasok')
                                    ->relationship('vendor','name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function(callable $set) {
                                        $set('purchase_id',null);
                                        $set('receiveItems', []);
                                    })
                                    ->required(),
                                DatePicker::make('receive_date')
                                    ->label('Tgl Penerimaan')
                                    ->required(),
                                TextInput::make('document_no')
                                    ->label('No Surat Jalan'),
                                DatePicker::make('document_date')
                                    ->label('Tgl Surat Jalan'),
                                Select::make('purchase_id')
                                    ->label('No PO')
                                    ->preload()
                                    ->searchable()
                                    ->options(function(callable $get) {
                                        $vendor = $get('vendor_id');
                                        if($vendor) {
                                            return Purchase::all()->where('vendor_id',$vendor)
                                                                    ->where('full_received','N')
                                                                    ->pluck('code','id');
                                        }
                                        return Purchase::all()->pluck('code','id');
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function(callable $set,$get,$state) {
                                        if(filled($state)) {
                                            $repeatItems = $get('receiveItems') ?? [];

                                            $poItems = PurchaseItem::where('purchase_id',$state)
                                                                    ->whereRaw('purchase_qty - receive_qty > 0')
                                                                    ->get();

                                            array_push(
                                                $repeatItems,
                                                ...$poItems->map(function ($poItem) {
                                                    return [
                                                        'item_name' => $poItem->item->name ?? null,
                                                        'uom_name' => $poItem->uom->code ?? null,
                                                        'purchase_qty' => $poItem->purchase_qty-$poItem->receive_qty ?? 0,
                                                        'receive_qty' => 0,
                                                        'expired_date' => null,
                                                        'note' => null,
                                                        'item_id' => $poItem->item_id,
                                                        'uom_id' => $poItem->uom_id,
                                                        'receive_price' => $poItem->purchase_price ?? 0,
                                                        'purchase_id' => $poItem->purchase_id ?? 0,
                                                        'purchase_item_id' => $poItem->id
                                                    ];
                                                })->toArray()
                                            );

                                            $set('receiveItems',$repeatItems);
                                        }
                                    })
                            ])->columns(5)
                    ])->columnSpan(5),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Repeater::make('receiveItems')
                                    ->label('Detail Penerimaan')
                                    ->relationship('receiveItems')
                                    ->table([
                                        TableColumn::make('Item')
                                            ->width('200px'),
                                        TableColumn::make('Satuan')
                                            ->width('100px'),
                                        TableColumn::make('Sisa PO')
                                            ->width('100px'),
                                        TableColumn::make('Qty Receive')
                                            ->width('100px'),
                                        TableColumn::make('Tgl Expire')
                                            ->width('125px'),
                                        TableColumn::make('Keterangan')
                                            ->width('200px')
                                    ])
                                    ->schema([
                                        TextInput::make('item_name')
                                            ->readOnly()
                                            ->dehydrated(false),
                                        TextInput::make('uom_name')
                                            ->readOnly()
                                            ->dehydrated(false),
                                        TextInput::make('purchase_qty')
                                            ->readonly()
                                            ->numeric()
                                            ->dehydrated(false),
                                        TextInput::make('receive_qty')
                                            ->numeric()
                                            ->lte('purchase_qty')
                                            ->default(0),
                                        DatePicker::make('expired_date'),
                                        TextInput::make('note'),
                                        Hidden::make('item_id'),
                                        Hidden::make('uom_id'),
                                        Hidden::make('receive_price'),
                                        Hidden::make('purchase_id'),
                                        Hidden::make('purchase_item_id'),
                                    ])
                                    ->defaultItems(0)
                                    ->minItems(0)
                                    ->addable(false)
                                    ->deletable(false)
                            ])
                    ])->columnSpan(5)
            ])
            ->columns(5);
    }
}
