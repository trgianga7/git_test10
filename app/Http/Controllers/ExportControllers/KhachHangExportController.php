<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\KhachHangExport;

class KhachHangExportController extends Controller
{
    public function export()
    {
        return Excel::download(new KhachHangExport, 'test10_khach_hang_export.xlsx');
    }
}