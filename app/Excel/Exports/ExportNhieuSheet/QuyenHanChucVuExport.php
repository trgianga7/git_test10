<?php

namespace App\Excel\Exports\ExportNhieuSheet;

//use App\Models\ChucVuModel;
//use App\Models\ChucNangModel;
use App\Excel\Exports\ChucVuExport;
use App\Excel\Exports\QuyenHanExport;
use Maatwebsite\Excel\Concerns\{
    WithMultipleSheets
};

class QuyenHanChucVuExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ChucVuExport(),       
            new QuyenHanExport(),     
        ];
    }
}