<?php

namespace App\Http\Controllers\ImportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Excel\Imports\DanhMucImport;
use Maatwebsite\Excel\Facades\Excel;

class DanhMucImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new DanhMucImport, $request->file('file'));

        return back()->with('success', 'Import thành công');
    }
    
}
