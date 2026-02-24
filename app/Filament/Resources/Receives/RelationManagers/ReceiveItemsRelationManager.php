<?php

namespace App\Filament\Resources\Receives\RelationManagers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMutation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ReceiveItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'receiveItems';

    protected static ?string $title = 'Detail Penerimaan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Item'),
                Select::make('uom_id')
                    ->relationship('uom', 'code')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Satuan'),
                TextInput::make('receive_qty')
                    ->numeric()
                    ->required()
                    ->label('Qty Terima'),
                TextInput::make('receive_price')
                    ->numeric()
                    ->required()
                    ->label('Harga'),
                DatePicker::make('expired_date')
                    ->label('Tgl Expire'),
                FileUpload::make('receive_image')
                    ->label('Gambar')
                    ->image()
                    ->maxFiles(1)
                    ->maxSize(1024)
                    ->disk('public')
                    ->directory('receive_images'),
                Hidden::make('purchase_id'),
                Hidden::make('purchase_item_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('item.name')
                    ->label('Item')
                    ->searchable(),
                TextColumn::make('uom.code')
                    ->label('Satuan'),
                TextColumn::make('receive_qty')
                    ->label('Qty Terima')
                    ->numeric(),
                TextColumn::make('receive_price')
                    ->label('Harga')
                    ->numeric(),
                TextColumn::make('expired_date')
                    ->label('Tgl Expire')
                    ->date(),
                ImageColumn::make('receive_image')
                    ->label('Gambar')
                    ->disk('public')
                    ->visibility('public'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        TextInput::make('receive_price')
                            ->numeric()
                            ->required()
                            ->label('Harga'),
                    ])
                    ->after(function (Model $record) {
                        $poItem = PurchaseItem::find($record->purchase_item_id);
                        if ($poItem) {
                            $poItem->purchase_price = $record->receive_price;
                            $poItem->receive_amount = $poItem->receive_qty * $record->receive_price;
                            $poItem->purchase_amount = $poItem->purchase_qty * $record->receive_price;
                            $poItem->update();

                            $record->item?->update(['last_purchase_price' => $record->receive_price]);
                        }
                    }),
                DeleteAction::make()
                    ->before(function (Model $record) {
                        $poItem = PurchaseItem::find($record->purchase_item_id);
                        if ($poItem) {
                            $poItem->receive_qty -= $record->receive_qty;
                            $poItem->receive_amount -= ($record->receive_qty * $record->receive_price);
                            $poItem->update();

                            $purchase = Purchase::find($poItem->purchase_id);
                            if ($purchase) {
                                $totalPO = $purchase->purchaseItems->sum('purchase_qty');
                                $totalReceive = $purchase->purchaseItems->sum('receive_qty');
                                if ($totalReceive < $totalPO) {
                                    $purchase->full_received = 'N';
                                    $purchase->update();
                                }
                            }
                        }

                        $stockMutation = StockMutation::where('item_id', $record->item_id)->first();
                        if ($stockMutation) {
                            $stockMutation->outgoing_qty += $record->receive_qty;
                            $stockMutation->update();
                            $stockMutation->setBalanceStock();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
