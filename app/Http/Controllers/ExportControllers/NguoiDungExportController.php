<?php

namespace App\Http\Controllers\ExportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\Exports\NguoiDungExport;

class NguoiDungExportController extends Controller
{
    public function export()
    {
        return Excel::download(new NguoiDungExport, 'test10_nguoi_dung_export.xlsx');
    }
}