<?php

use App\Http\Controllers\DownloadPdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{record}/po/download', [DownloadPdf::class,'downloadPO'])->name('po.pdf.download');
