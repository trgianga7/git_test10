<?php

namespace App\Http\Controllers\ImportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Excel\Imports\HoaDonImport;
use App\Excel\Imports\HoaDonImport;
use Maatwebsite\Excel\Facades\Excel;

class HoaDonImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new HoaDonImport, $request->file('file'));

        return back()->with('success', 'Import thành công');
    }
    
}
