<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\DanhMucExport;

class DanhMucExportController extends Controller
{
    public function export()
    {
        return Excel::download(new DanhMucExport, 'test10_danh_muc_export.xlsx');
    }
}