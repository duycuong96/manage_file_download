<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PdfMerger;

class MergerPdfController extends Controller
{
    public function mergerPdf(){
        $pdfMerger = PdfMerger::init();
        $pdftext = file_get_contents(storage_path('app/15/new/Buoi1.pdf'));
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        $pdfMerger->addPDF(storage_path('app/15/new/Buoi1.pdf'), '1-10');
        dd($num);

        // dd($num);
        $pdfMerger->addPDF(storage_path('app/15/new/誌管理＠＃＄-20200611142841.pdf'), 'all');
        $pdfMerger->addPDF(storage_path('app/15/new/Buoi1.pdf'), '10-'.$num);
        // dd($pdfMerger);

        $pdfMerger->merge();

        $pdfMerger->save(storage_path('app/mergerPdf/mergerPdf.pdf'), "file");

    }
}
