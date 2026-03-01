<?php

namespace App\Filament\Resources\StockMutations\Tables;

use App\Models\StockAdjustment;
use App\Models\StockMutation;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMutationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.code')
                    ->label('No. Item')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.uom.code')
                    ->label('Satuan')
                    ->sortable(),
                TextColumn::make('receive_qty')
                    ->label('Qty Masuk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('outgoing_qty')
                    ->label('Qty Keluar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance_qty')
                    ->label('Saldo Stok')
                    ->numeric()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('setBalance')
                    ->label('Set Balance')
                    ->icon('heroicon-o-calculator')
                    ->form([
                        TextInput::make('new_balance')
                            ->label('Saldo Stok Baru')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function (StockMutation $record, array $data) {
                        $currentBalance = (float) $record->balance_qty;
                        $newBalance = (float) $data['new_balance'];
                        $diff = $newBalance - $currentBalance;
                        $user = auth()->user();

                        if ($diff == 0) {
                            Notification::make()
                                ->title('Tidak Ada Perubahan')
                                ->body('Saldo stok yang diinput sama dengan saldo saat ini.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $adjustmentType = $diff > 0 ? 'plus' : 'minus';
                        $adjustmentQty = abs($diff);

                        StockAdjustment::create([
                            'item_id' => $record->item_id,
                            'user_id' => $user->id,
                            'uom_id' => $record->item->uom_id,
                            'adjustment_date' => now(),
                            'adjustment_qty' => $adjustmentQty,
                            'adjustment_type' => $adjustmentType,
                            'adjustment_reason' => 'adjustment stock done by '.$user->name,
                        ]);

                        if ($adjustmentType === 'plus') {
                            $record->receive_qty += $adjustmentQty;
                        } else {
                            $record->outgoing_qty += $adjustmentQty;
                        }
                        $record->setBalanceStock();

                        Notification::make()
                            ->title('Berhasil')
                            ->body("Saldo disesuaikan dari {$currentBalance} menjadi {$newBalance}")
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
