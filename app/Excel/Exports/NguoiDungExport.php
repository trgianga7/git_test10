<?php

namespace App\Excel\Exports;

use App\Models\NguoiDungModel;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class NguoiDungExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return NguoiDungModel::all();
    }

    public function map($nguoiDung): array
    {
        return [
            $nguoiDung->id,
            $nguoiDung->chucvu->ten_chuc_vu,
            $nguoiDung->ten_nguoi_dung,
            $nguoiDung->email,
            $nguoiDung->mat_khau,
            $nguoiDung->trang_thai,
            $nguoiDung->ngay_tao,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Chức vụ',
            'Tên người dùng',
            'Email',
            'Mật khẩu',
            'Trạng thái',
            'Ngày tạo'
        ];
    }
}
