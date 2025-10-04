<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Purchase;
use App\Traits\HasNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

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
                'Tipe' => $this->po->po_type
            ]
        ]);

        $customer = new Buyer([
            'custom_fields' => [
                'val1' => 'Kepada :',
                'val2' => $vendor->name, 
                'val3' => $vendor->address1,
                'val4' => $vendor->address2,
                'val5' => $vendor->address3       
            ],
        ]);

        $items = array();

        foreach ($poItems as $item) {
            $items[] =
                (new InvoiceItem())
                ->title($item->item->code)
                ->description($item->item->name)
                ->pricePerUnit($item->purchase_price)
                ->quantity($item->purchase_qty)
                ->units($item->uom->code);
        }

        $notes = [];
        $notes = implode("<br>", $notes);

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
}
