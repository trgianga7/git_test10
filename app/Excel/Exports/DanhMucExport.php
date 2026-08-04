<?php

namespace App\Excel\Exports;

use App\Models\DanhMucModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class DanhMucExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DanhMucModel::all();
    }

    public function map($danhMuc): array
    {
        return [
            $danhMuc->id,
            $danhMuc->ten_danh_muc,
            $danhMuc->trang_thai,
            $danhMuc->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Tên danh mục',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
