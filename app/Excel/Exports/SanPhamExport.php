<?php

namespace App\Excel\Exports;

use App\Models\SanPhamModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class SanPhamExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SanPhamModel::all();
    }

    public function map($sanPham): array
    {
        return [
            $sanPham->id,
            $sanPham->danhmuc->ten_danh_muc,
            $sanPham->ten_san_pham,
            $sanPham->trang_thai,
            $sanPham->ngay_tao,
            $sanPham->nguoidung->ten_nguoi_dung,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Tên danh mục',
            'Tên sản phẩm',
            'Trạng thái',
            'Ngày tạo',
            'Người tạo',
        ];
    }
}
