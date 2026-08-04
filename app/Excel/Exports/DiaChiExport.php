<?php

namespace App\Excel\Exports;

use App\Models\DiaChiModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class DiaChiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DiaChiModel::all();
    }

    public function map($diaChi): array
    {
        return [
            $diaChi->id,
            $diaChi->khachhang->ten_khach_hang,
            $diaChi->tinh_ten->province_name,
            $diaChi->huyen_ten->district_name,
            $diaChi->phuong_ten->ward_name,
            $diaChi->dia_chi,
            $diaChi->trang_thai,
            $diaChi->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Tên khách hàng',
            'Tỉnh',
            'Huyện',
            'Phường',
            'Địa chỉ',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
