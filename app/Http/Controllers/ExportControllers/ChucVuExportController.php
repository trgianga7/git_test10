<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\ChucVuExport;

class ChucVuExportController extends Controller
{
    public function export()
    {
        return Excel::download(new ChucVuExport, 'test10_chuc_vu_export.xlsx');
    }
}