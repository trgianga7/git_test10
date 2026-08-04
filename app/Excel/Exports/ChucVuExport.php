<?php

namespace App\Excel\Exports;

use App\Models\ChucVuModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class ChucVuExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ChucVuModel::all();
    }

    public function map($chucVu): array
    {
        return [
            $chucVu->id,
            $chucVu->ten_chuc_vu,
            $chucVu->trang_thai,
            $chucVu->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Tên chức vụ',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
