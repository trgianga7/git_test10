<?php

namespace App\Excel\Exports;

use App\Models\KhachHangModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class KhachHangExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return KhachHangModel::where('loai_khach_hang', '!=', 0)->get();
    }

    public function map($khachHang): array
    {   
        $loaiKhachHang = [
            1 => 'Khách thường(1)',
            2 => 'Khách đặc biệt(2)',
        ];

        return [
            $khachHang->id,
            $loaiKhachHang[$khachHang->loai_khach_hang] ?? '',
            $khachHang->ten_khach_hang,
            $khachHang->sdt,
            $khachHang->mat_khau,
            $khachHang->trang_thai,
            $khachHang->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Loại khách hàng',
            'Tên khách hàng',
            'Sdt',
            'Mật khẩu',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
