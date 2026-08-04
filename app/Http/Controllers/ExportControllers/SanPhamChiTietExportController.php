<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\SanPhamChiTietExport;

class SanPhamChiTietExportController extends Controller
{
    public function export()
    {
        return Excel::download(new SanPhamChiTietExport, 'test10_san_pham_chi_tiet_export.xlsx');
    }
}