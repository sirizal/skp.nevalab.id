<?php

namespace App\Filament\Resources\Receives\Pages;

use App\Filament\Resources\Receives\ReceiveResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Receive;
use App\Models\ReceiveItem;
use App\Models\StockMutation;
use Filament\Resources\Pages\CreateRecord;

class CreateReceive extends CreateRecord
{
    protected static string $resource = ReceiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (! isset($data['code'])) {
            $record = Receive::withTrashed()->latest('id')->first();

            $prefix = 'GRN-SKP-';

            if ($record === null or $record === '') {
                $requestNo = $prefix.date('ym').'-001';
            } else {
                $expNum = explode('-', $record->code);
                if (date('ym') === $expNum[2]) {
                    $number = ($expNum[3] + 1);
                    $requestNo = $prefix.date('ym').'-'.str_pad($number, 3, 0, STR_PAD_LEFT);
                } else {
                    $requestNo = $prefix.date('ym').'-001';
                }
            }

            $data['code'] = $requestNo;
        }

        $data['document_date'] = $data['receive_date'];

        if (! empty($data['purchase_id'])) {
            $purchase = Purchase::with('vendor')->find($data['purchase_id']);
            if ($purchase) {
                $yymm = date('ym', strtotime($purchase->purchase_date));
                $sjPrefix = $purchase->vendor?->sj_prefix ?? 'SJ';

                $lastDocNo = Receive::withTrashed()->where('document_no', 'like', "{$sjPrefix}-{$yymm}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastDocNo) {
                    $expDocNum = explode('-', $lastDocNo->document_no);
                    $docNumber = (int) end($expDocNum) + 1;
                } else {
                    $docNumber = 1;
                }

                $data['document_no'] = $sjPrefix.'-'.$yymm.'-'.str_pad($docNumber, 3, '0', STR_PAD_LEFT);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $receive = Receive::find($this->record->id);
        $receiveItems = $receive->receiveItems()->with('item')->where('receive_qty', '>', 0)->get();
        foreach ($receiveItems as $value) {
            $stockMutation = StockMutation::where('item_id', $value->item_id)->first();
            $smId = $stockMutation->id ?? 0;
            $receiveItem = ReceiveItem::where('id', $value->id)->first();
            $receiveItem->remain_qty = $value->receive_qty;
            $receiveItem->update();
            $poItem = PurchaseItem::where('id', $value->purchase_item_id)->first();
            $poItem->receive_qty += $value->receive_qty;
            $poItem->receive_amount += ($value->receive_qty * $value->receive_price);
            $poItem->update();
            $value->item?->update(['last_purchase_price' => $poItem->purchase_price]);
            if ($smId > 0) {
                $stockMutation->receive_qty += $value->receive_qty;
                $stockMutation->update();
                $stockMutation->setBalanceStock();
            } else {
                $smCreate = StockMutation::create([
                    'item_id' => $value->item_id,
                    'receive_qty' => $value->receive_qty,
                ]);
                $smCreate->setBalanceStock();
            }
        }

        $purchase = Purchase::find($this->record->purchase_id);
        $totalPO = $purchase->purchaseItems->sum('purchase_qty');
        $totalReceive = $purchase->purchaseItems->sum('receive_qty');
        if ($totalPO === $totalReceive) {
            $purchase->full_received = 'Y';
            $purchase->update();
        }
    }
}
