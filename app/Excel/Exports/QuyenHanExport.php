<?php

namespace App\Excel\Exports;

use App\Models\ChucVuModel;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping};

class QuyenHanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ChucVuModel::with('chucNangs')->get();
    }

    public function map($chucVu): array
    {
        $quyenHan = $chucVu->chucNangs->pluck('ten_chuc_nang')->implode(', ');

        return [
            $chucVu->id,
            $chucVu->ten_chuc_vu,
            $quyenHan,
        ];
    }

    public function headings(): array
    {
        return [
            'ID Chức vụ',
            'Tên chức vụ',
            'Quyền hạn',
        ];
    }
}