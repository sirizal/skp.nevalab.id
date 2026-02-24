<?php

namespace App\Filament\Resources\Receives\Pages;

use App\Filament\Resources\Receives\ReceiveResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Receive;
use App\Models\ReceiveItem;
use App\Models\StockMutation;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListReceives extends ListRecords
{
    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bulkReceive')
                ->label('Bulk Receive')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Bulk Receive dari PO')
                ->modalDescription('Proses semua PO yang belum full received menjadi Receive?')
                ->action(function () {
                    $purchases = Purchase::where('full_received', 'N')->get();
                    $count = 0;

                    foreach ($purchases as $purchase) {
                        $poItems = PurchaseItem::where('purchase_id', $purchase->id)
                            ->whereRaw('purchase_qty - receive_qty > 0')
                            ->get();

                        if ($poItems->isEmpty()) {
                            continue;
                        }

                        $yymm = date('ym', strtotime($purchase->purchase_date));

                        $lastReceive = Receive::where('code', 'like', "GRN-SKP-{$yymm}-%")
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($lastReceive) {
                            $expNum = explode('-', $lastReceive->code);
                            $number = (int) $expNum[3] + 1;
                        } else {
                            $number = 1;
                        }

                        $code = 'GRN-SKP-'.$yymm.'-'.str_pad($number, 3, '0', STR_PAD_LEFT);

                        $sjPrefix = $purchase->vendor->sj_prefix ?? 'SJ';
                        $lastDocNo = Receive::where('document_no', 'like', "{$sjPrefix}-{$yymm}-%")
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($lastDocNo) {
                            $expDocNum = explode('-', $lastDocNo->document_no);
                            $docNumber = (int) end($expDocNum) + 1;
                        } else {
                            $docNumber = 1;
                        }

                        $documentNo = $sjPrefix.'-'.$yymm.'-'.str_pad($docNumber, 3, '0', STR_PAD_LEFT);

                        $receive = Receive::create([
                            'code' => $code,
                            'receive_date' => $purchase->purchase_date,
                            'vendor_id' => $purchase->vendor_id,
                            'document_date' => $purchase->purchase_date,
                            'document_no' => $documentNo,
                            'purchase_id' => $purchase->id,
                            'user_id' => $purchase->user_id ?? auth()->id(),
                        ]);

                        foreach ($poItems as $poItem) {
                            $receiveQty = $poItem->purchase_qty - $poItem->receive_qty;

                            $receiveItem = ReceiveItem::create([
                                'receive_id' => $receive->id,
                                'item_id' => $poItem->item_id,
                                'uom_id' => $poItem->uom_id,
                                'receive_qty' => $receiveQty,
                                'receive_price' => $poItem->purchase_price,
                                'purchase_id' => $purchase->id,
                                'purchase_item_id' => $poItem->id,
                                'remain_qty' => $receiveQty,
                            ]);

                            $poItem->receive_qty += $receiveQty;
                            $poItem->receive_amount += ($receiveQty * $poItem->purchase_price);
                            $poItem->update();

                            $poItem->item?->update(['last_purchase_price' => $poItem->purchase_price]);

                            $stockMutation = StockMutation::where('item_id', $poItem->item_id)->first();
                            if ($stockMutation) {
                                $stockMutation->receive_qty += $receiveQty;
                                $stockMutation->update();
                                $stockMutation->setBalanceStock();
                            } else {
                                $smCreate = StockMutation::create([
                                    'item_id' => $poItem->item_id,
                                    'receive_qty' => $receiveQty,
                                ]);
                                $smCreate->setBalanceStock();
                            }
                        }

                        $totalPO = $purchase->purchaseItems->sum('purchase_qty');
                        $totalReceive = $purchase->purchaseItems->sum('receive_qty');
                        if ($totalPO === $totalReceive) {
                            $purchase->full_received = 'Y';
                            $purchase->update();
                        }

                        $count++;
                    }

                    if ($count > 0) {
                        Notification::make()
                            ->title('Berhasil')
                            ->body("{$count} Receive berhasil dibuat.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Tidak ada PO')
                            ->body('Tidak ada PO yang perlu diproses.')
                            ->warning()
                            ->send();
                    }
                }),
            CreateAction::make()
                ->label('Input Penerimaan'),
        ];
    }
}
