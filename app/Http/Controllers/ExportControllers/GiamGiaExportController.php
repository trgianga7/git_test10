<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\GiamGiaExport;

class GiamGiaExportController extends Controller
{
    public function export()
    {
        return Excel::download(new GiamGiaExport, 'test10_giam_gia_export.xlsx');
    }
}