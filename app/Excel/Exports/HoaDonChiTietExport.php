<?php

namespace App\Excel\Exports;

use App\Models\HoaDonCtModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class HoaDonChiTietExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return HoaDonCtModel::all();
    }

    public function map($hoaDonCt): array
    {
        return [
            $hoaDonCt->id,
            $hoaDonCt->hoadon->ma_hd ?? 'Không có',
            $hoaDonCt->id_san_pham_chi_tiet,
            $hoaDonCt->ten_san_pham,
            $hoaDonCt->gia_ban,
            $hoaDonCt->so_luong,
            $hoaDonCt->tong_tien_hd, 
            $hoaDonCt->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Mã HĐ',
            'Id sản phẩm chi tiết',
            'Tên sản phẩm',
            'Giá bán',
            'Số lượng',
            'Tổng tiền hóa đơn',
            'Ngày tạo',
        ];
    }
}
