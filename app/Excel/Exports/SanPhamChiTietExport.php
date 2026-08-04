<?php

namespace App\Excel\Exports;

use App\Models\SanPhamChiTietModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class SanPhamChiTietExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SanPhamChiTietModel::all();
    }

    public function map($sanPhamCt): array
    {
        return [
            $sanPhamCt->id,
            $sanPhamCt->ma_sp,
            $sanPhamCt->sanpham->ten_san_pham,
            $sanPhamCt->thong_tin_1,
            $sanPhamCt->thong_tin_2,
            $sanPhamCt->gia_goc,
            $sanPhamCt->gia_ban,
            $sanPhamCt->so_luong,
            $sanPhamCt->trang_thai,
            $sanPhamCt->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Mã Sp',
            'Tên sản phẩm',
            'Thông tin 1',
            'Thông tin 2',
            'Giá gốc',
            'Giá bán',
            'Số lượng',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
