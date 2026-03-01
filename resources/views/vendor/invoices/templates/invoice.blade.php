<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $invoice->name }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style type="text/css" media="screen">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 450;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 12px;
                margin: 25pt;
            }

            h4 {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            p {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            strong {
                font-weight: bolder;
            }

            img {
                vertical-align: middle;
                border-style: none;
            }

            table {
                border-collapse: collapse;
            }

            th {
                text-align: inherit;
            }

            h4, .h4 {
                margin-bottom: 0.2rem;
                font-weight: 500;
                line-height: 1.1;
            }

            h4, .h4 {
                font-size: 1.5rem;
            }

            .table {
                width: 100%;
                margin-bottom: 0.5rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.3rem;
                vertical-align: top;
            }

            .table.table-items td {
                border-top: 1px solid #dee2e6;
            }

            .table.table-footer td {
                padding: 0;
                vertical-align: top;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .mt-5 {
                margin-top: 5rem !important;
            }

            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }

            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-uppercase {
                text-transform: uppercase !important;
            }
            * {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 1.5rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .cool-gray {
                color: #6B7280;
            }
        </style>
    </head>

    <body>
        {{-- Header --}}
        
        <table class="table" style="border-bottom: 2px solid #dee2e6;">
            <thead>
                <tr>
                    <th scope="col" class="text-center border-0" width="20%" style="vertical-align: middle;">
                        <img src="{{ $invoice->getLogo() }}" alt="logo" width="100%" style="max-width: 100px; height: auto;">
                    </th>
                    <th scope="col" class="border-0" width="80%" style="vertical-align: middle;">
                        <h4>{{ $invoice->seller->name }}</h4>
                        <p>{{ $invoice->seller->address }}</p>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table">
            <tbody>
                <tr>                  
                    <td class="border-0 pl-0">
                        <h4 class="text-uppercase text-center">
                            <strong>{{ $invoice->name }}</strong>
                        </h4>
                    </td> 
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table">
            <tbody>
                <tr>
                    <td class="px-0">
                        @foreach($invoice->seller->custom_fields as $key => $value)
                            @if($key !== 'Terbilang')
                            <p class="seller-custom-field">
                                {{ ucfirst($key) }}: <strong> {{ $value }} </strong>
                            </p>
                            @endif
                        @endforeach
                    </td>
                    <td class="border-0"></td>
                    <td class="pr-0">
                        @foreach($invoice->buyer->custom_fields as $key => $value)
                            <p class="buyer-custom-field">
                                <strong> {{ $value }} </strong>
                            </p>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="border-0 pl-0" width="5%">No</th>
                    <th scope="col" class="text-center border-0" width="10%">Kode Item</th>
                    <th scope="col" class="text-center border-0" width="30%">Nama Barang</th>
                    <th scope="col" class="text-center border-0" width="8%">Satuan</th>
                    <th scope="col" class="text-center border-0" width="8%">Qty</th>
                    <th scope="col" class="text-center border-0" width="15%">Harga</th>
                    <th scope="col" class="text-center border-0" width="24%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="pl-0">
                        {{ $index+1 }}
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->units }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                    <td class="text-right">{{ $invoice->formatCurrency($item->price_per_unit) ?? 0 }}</td>
                    <td class="text-right pr-0">
                        {{ $invoice->formatCurrency($item->sub_total_price) }}
                    </td>
                </tr>
                @endforeach
                {{-- Summary --}}
                <tr>
                    <td colspan="5" class="border-0" width="61%"></td>
                    <td class="text-right pl-0" width="15%">Total</td>
                    <td class="text-right pr-0 total-amount" width="24%">
                        {{ $invoice->formatCurrency($invoice->total_amount) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left pl-0" colspan="7" style="border-top: none;">Terbilang:
                        <span style="font-style: italic; color: black;">
                            {{ $invoice->seller->custom_fields['Terbilang'] ?? '' }}
                        </span>
                    </td>
                </tr>
                    
            </tbody>
        </table>

        <table class="table table-footer">
            <tbody>
                <tr>
                    <td class="pl-0" width="50%" valign="top">
                        <strong>Hormat Kami</strong>
                        <p></p>
                        <p></p>
                        <p></p>
                        <p></p>
                        <p></p>
                        <p></p>
                    </td>
                    <td class="pr-0" width="50%" valign="top">
                        @if($invoice->notes)
                            {!! $invoice->notes !!}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        
        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
