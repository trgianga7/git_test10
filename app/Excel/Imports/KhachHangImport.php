<?php

namespace App\Excel\Imports;

use App\Models\KhachHangModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\{
    ToModel, 
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class KhachHangImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new KhachHangModel([
            'loai_khach_hang' => $row["Loại khách hàng"],
            'ten_khach_hang' => $row['Tên khách hàng'],
            'sdt' => $row["Sdt"],
            'mat_khau' => Hash::make($row['Mật khẩu']),
            'loai_tai_khoan' => 0,
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Loại khách hàng' => 'required|integer',
            '*.Tên khách hàng' => 'required|string|max:255',
            '*.Sdt' => 'required|string|max:255|unique:khach_hang,sdt',
            '*.Mật khẩu' => 'required|min:6',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Sdt.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
        ];
    }
}
