<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\HoaDonExport;

class HoaDonExportController extends Controller
{
    public function export()
    {
        return Excel::download(new HoaDonExport, 'test10_hoa_don_export.xlsx');
    }
}