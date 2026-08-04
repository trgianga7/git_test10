<?php

namespace App\Excel\Imports;

use App\Models\ChucVuModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ChucVuImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new ChucVuModel([
            'ten_chuc_vu' => $row['Tên chức vụ'],
            'quyen_han' => $row['Quyền hạn'],
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên chức vụ' => 'required|string|max:255|unique:chuc_vu,ten_chuc_vu',
            '*.Quyền hạn'   => 'required|string|in:Admin,Quản lý,Nhân viên,Người bán hàng',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Tên chức vụ.unique' => 'Chức vụ đã tồn tại trong hệ thống',
        ];
    }
}
