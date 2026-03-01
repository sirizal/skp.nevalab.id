<?php

namespace App\Filament\Resources\Receives\Pages;

use App\Filament\Resources\Receives\ReceiveResource;
use App\Models\Receive;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditReceive extends EditRecord
{
    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createInvoice')
                ->label('Buat Invoice')
                ->color('success')
                ->visible(fn () => empty($this->record->invoice_no))
                ->requiresConfirmation()
                ->modalHeading('Buat Invoice')
                ->modalDescription('Proses pembuatan invoice untuk penerimaan ini?')
                ->action(function () {
                    $receive = Receive::find($this->record->id);
                    $yymm = date('ym', strtotime($receive->receive_date));
                    $invPrefix = $receive->purchase?->vendor?->inv_prefix ?? 'INV';

                    $lastInvoice = Receive::withTrashed()->where('invoice_no', 'like', "{$invPrefix}-{$yymm}-%")
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($lastInvoice) {
                        $expNum = explode('-', $lastInvoice->invoice_no);
                        $number = (int) end($expNum) + 1;
                    } else {
                        $number = 1;
                    }

                    $invoiceNo = $invPrefix.'-'.$yymm.'-'.str_pad($number, 3, '0', STR_PAD_LEFT);

                    $receive->update([
                        'invoice_no' => $invoiceNo,
                        'invoice_date' => $receive->receive_date,
                    ]);

                    Notification::make()
                        ->title('Berhasil')
                        ->body("Invoice {$invoiceNo} berhasil dibuat.")
                        ->success()
                        ->send();
                }),
            Action::make('printInvoice')
                ->label('Print Invoice')
                ->icon('heroicon-o-document-arrow-down')
                ->visible(fn () => ! empty($this->record->invoice_no))
                ->url(fn () => route('receive.invoice.download', ['record' => $this->record]))
                ->openUrlInNewTab(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
