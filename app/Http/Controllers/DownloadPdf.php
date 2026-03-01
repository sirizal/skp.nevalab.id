<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Receive;
use App\Models\Vendor;
use App\Traits\HasNumber;
use Illuminate\Support\Carbon;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class DownloadPdf extends Controller
{
    use HasNumber;

    public Purchase $po;

    public function DownloadPo(Purchase $record)
    {
        $this->po = $record;
        $poItems = $this->po->purchaseItems;

        $poDate = Carbon::parse($this->po->purchase_date);
        $vendor = Vendor::find($this->po->vendor_id);

        $seller = new Party([
            'name' => 'SPPG PONDOK RANJI,CIPUTAT TIMUR #003',
            'address' => 'Jl.Rusa 5A , RT.003 / RW.004 Pondok Ranji , Ciputat Timur Tangerang Selatan , Banten 15412 ',
            'custom_fields' => [
                'No PO' => $this->po->code === '' ? $this->po->code : $this->po->code,
                'Tanggal' => $poDate->format('d M Y'),
                'Tipe' => $this->po->po_type,
            ],
        ]);

        $customer = new Buyer([
            'custom_fields' => [
                'val1' => 'Kepada :',
                'val2' => $vendor->name,
                'val3' => $vendor->address1,
                'val4' => $vendor->address2,
                'val5' => $vendor->address3,
            ],
        ]);

        $items = [];

        foreach ($poItems as $item) {
            $items[] =
                (new InvoiceItem)
                    ->title($item->item->code)
                    ->description($item->item->name)
                    ->pricePerUnit($item->purchase_price)
                    ->quantity($item->purchase_qty)
                    ->units($item->uom->code);
        }

        $notes = [];
        $notes = implode('<br>', $notes);

        $invoice = Invoice::make('po')
            ->name('PURCHASE ORDER')
            ->seller($seller)
            ->buyer($customer)
            ->series($this->po->code)
            ->serialNumberFormat('{SERIES}')
            ->date(Carbon::createFromFormat('Y-m-d', $this->po->purchase_date))
            ->dateFormat('d/m/Y')
            ->currencySymbol('Rp')
            ->currencyCode('IDR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyDecimals(0)
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/bgn_logo.jpeg'))
            ->filename($this->po->code)
            ->template('po');

        return $invoice->stream();

    }

    public function DownloadInvoice(Receive $record)
    {
        $receive = $record;
        $receiveItems = $receive->receiveItems;

        $invoiceDate = Carbon::parse($receive->invoice_date);
        $vendor = $receive->vendor;

        $items = [];

        foreach ($receiveItems as $item) {
            $items[] =
                (new InvoiceItem)
                    ->title($item->item->code)
                    ->description($item->item->name)
                    ->pricePerUnit($item->receive_price)
                    ->quantity($item->receive_qty)
                    ->units($item->uom->code);
        }

        $totalAmount = 0;
        foreach ($receiveItems as $ri) {
            $totalAmount += $ri->receive_price * $ri->receive_qty;
        }
        $totalTerbilang = strtoupper($this->pembilang($totalAmount).' RUPIAH');

        $seller = new Party([
            'name' => $vendor->name,
            'address' => $vendor->address1.' '.$vendor->address2.' '.$vendor->address3,
            'custom_fields' => [
                'No Invoice' => $receive->invoice_no,
                'Tanggal' => $invoiceDate->format('d M Y'),
                'PO No' => $receive->purchase?->code,
                'Terbilang' => $totalTerbilang,
            ],
        ]);

        $customer = new Buyer([
            'custom_fields' => [
                'val1' => 'Kepada :',
                'val2' => 'SPPG PONDOK RANJI,CIPUTAT TIMUR #003',
                'val3' => 'Jl.Rusa 5A , RT.003 / RW.004 Pondok Ranji',
                'val4' => 'Ciputat Timur Tangerang Selatan',
                'val5' => 'Banten 15412 ',
            ],
        ]);

        $items = [];

        foreach ($receiveItems as $item) {
            $items[] =
                (new InvoiceItem)
                    ->title($item->item->code)
                    ->description($item->item->name)
                    ->pricePerUnit($item->receive_price)
                    ->quantity($item->receive_qty)
                    ->units($item->uom->code);
        }

        $logoPath = $vendor->logo
            ? 'vendor/invoices/'.$vendor->logo
            : 'vendor/invoices/bgn_logo.jpeg';

        $totalAmount = collect($items)->sum('pricePerUnit') * collect($items)->sum('quantity');
        $totalTerbilang = strtoupper($this->pembilang($totalAmount).' RUPIAH');

        $notes = [];
        if ($vendor->bank_name || $vendor->account_no || $vendor->account_holder_name) {
            $notes[] = '<strong>Informasi Pembayaran:</strong>';
            if ($vendor->bank_name) {
                $notes[] = 'Bank: '.$vendor->bank_name;
            }
            if ($vendor->account_no) {
                $notes[] = 'No. Rekening: '.$vendor->account_no;
            }
            if ($vendor->account_holder_name) {
                $notes[] = 'Nama Pemilik: '.$vendor->account_holder_name;
            }
        }
        $notesString = implode('<br>', $notes);

        $invoice = Invoice::make('invoice')
            ->name('INVOICE')
            ->seller($seller)
            ->buyer($customer)
            ->series($receive->invoice_no)
            ->serialNumberFormat('{SERIES}')
            ->date(Carbon::createFromFormat('Y-m-d', $receive->invoice_date))
            ->dateFormat('d/m/Y')
            ->currencySymbol('Rp')
            ->currencyCode('IDR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyDecimals(0)
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->notes($notesString)
            ->logo(public_path($logoPath))
            ->filename($receive->invoice_no)
            ->template('invoice');

        return $invoice->stream();
    }
}
