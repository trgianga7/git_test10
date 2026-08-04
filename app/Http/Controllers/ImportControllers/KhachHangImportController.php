<?php

namespace App\Http\Controllers\ImportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Excel\Imports\KhachHangImport;
use Maatwebsite\Excel\Facades\Excel;

class KhachHangImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new KhachHangImport, $request->file('file'));

        return back()->with('success', 'Import thành công');
    }
    
}
