<?php

namespace App\Http\Controllers\ImportControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Excel\Imports\ChucVuImport;
use Maatwebsite\Excel\Facades\Excel;

class ChucVuImportController extends Controller
{
    public function import(Request $request)
    {   
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        //dd($request);

        Excel::import(new ChucVuImport, $request->file('file'));

        return back()->with('success', 'Import thành công');
    }
}
