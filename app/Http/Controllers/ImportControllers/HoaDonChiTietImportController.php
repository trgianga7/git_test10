<?php

namespace App\Http\Controllers\ImportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Excel\Imports\HoaDonChiTietImport;
use Maatwebsite\Excel\Facades\Excel;

class HoaDonChiTietImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new HoaDonChiTietImport, $request->file('file'));

        return back()->with('success', 'Import thành công');
    }
    
}
