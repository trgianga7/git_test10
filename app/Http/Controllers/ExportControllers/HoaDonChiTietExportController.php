<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\HoaDonChiTietExport;

class HoaDonChiTietExportController extends Controller
{
    public function export()
    {
        return Excel::download(new HoaDonChiTietExport, 'test10_hoa_don_chi_tiet_export.xlsx');
    }
}