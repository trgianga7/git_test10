<?php

namespace App\Http\Controllers\ExportControllers\ExportNhieuSheetController;

use App\Http\Controllers\Controller;
use App\Excel\Exports\ExportNhieuSheet\QuyenHanChucVuExport;
use Maatwebsite\Excel\Facades\Excel;

class QuyenHanChucVuExportController extends Controller
{
    public function exportChucVu()
    {
        return Excel::download(new QuyenHanChucVuExport, 'test10_chuc_vu_export.xlsx');
    }
}