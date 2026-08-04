<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\SanPhamExport;

class SanPhamExportController extends Controller
{
    public function export()
    {
        return Excel::download(new SanPhamExport, 'test10_san_pham_export.xlsx');
    }
}