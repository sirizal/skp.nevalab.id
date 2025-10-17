<?php

namespace App\Filament\Resources\Purchases\Tables;

use App\Models\Purchase;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Carbon;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('No PO')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vendor.name')
                    ->label('Pemasok')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->label('Tgl PO')
                    ->date()
                    ->sortable(),
                TextColumn::make('po_type')
                    ->label('Tipe PO')
                    ->searchable(),
                TextColumn::make('purchase_items_sum_purchase_amount')
                    ->sum('purchaseItems','purchase_amount')
                    ->label('Nilai PO')
                    ->numeric()
                    ->summarize([
                        Sum::make()
                    ])
                    ->sortable(),
                TextColumn::make('purchase_items_sum_receive_amount')
                    ->sum('purchaseItems','receive_amount')
                    ->label('Nilai Penerimaan')
                    ->wrapHeader()
                    ->numeric()
                    ->summarize([
                        Sum::make()
                    ])
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id','desc')
            ->filters([
                TrashedFilter::make(),
                Filter::make('purchase_date')
                    ->label('Tgl PO')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label('Tgl PO dari'),
                        DatePicker::make('created_until')
                            ->label('Tgl PO Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('purchase_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('purchase_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Tgl PO dari ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Tgl PO Sampai ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

            ])
            ->recordActions([
                EditAction::make(),
                Action::make('po')
                    ->label('Print PO')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Purchase $record): string => route('po.pdf.download', ['record' => $record]))
                    ->openUrlInNewTab()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
