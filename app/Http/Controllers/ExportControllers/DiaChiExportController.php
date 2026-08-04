<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\DiaChiExport;

class DiaChiExportController extends Controller
{
    public function export()
    {
        return Excel::download(new DiaChiExport, 'test10_dia_chi_export.xlsx');
    }
}