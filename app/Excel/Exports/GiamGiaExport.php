<?php

namespace App\Excel\Exports;

use App\Models\GiamGiaModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class GiamGiaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return GiamGiaModel::all();
    }

    public function map($giamGia): array
    {
        return [
            $giamGia->id,
            $giamGia->loai_giam_gia ? 'Giảm giá phần trăm': 'Giảm giá cố định',
            $giamGia->ma_giam_gia,
            $giamGia->gia_tri,
            $giamGia->ngay_het_han,
            $giamGia->trang_thai,
            $giamGia->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Loại giảm giá',
            'Mã giảm giá',
            'Giá trị giảm',
            'Ngày hết hạn',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
