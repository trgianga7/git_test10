<?php

namespace App\Excel\Exports;

use App\Models\HoaDonModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class HoaDonExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return HoaDonModel::all();
    }

    public function map($hoaDon): array
    {
        //dd($hoaDon);
        return [
            $hoaDon->id,
            $hoaDon->ma_hd,
            $hoaDon->khachhang->ten_khach_hang,
            $hoaDon->dia_chi_hd,
            $hoaDon->tong_tien_goc,
            $hoaDon->giam_gia,
            $hoaDon->tong_tien_thuc, 
            $hoaDon->loai_hinh ? 'Bán tại cửa hàng':'Đặt hàng trực tuyến',
            $hoaDon->trangthaihd->trang_thai,
            $hoaDon->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Mã HĐ',
            'Tên khách hàng',
            'Địa chỉ hóa đơn',
            'Tổng tiền gốc',
            'Giảm giá',
            'Tổng tiền thực',
            'Loại hình',
            'Trạng thái',
            'Ngày tạo',
        ];
    }
}
