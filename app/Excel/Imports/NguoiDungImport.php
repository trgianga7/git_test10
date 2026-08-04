<?php

namespace App\Excel\Imports;

use App\Models\NguoiDungModel;
use App\Models\ChucVuModel;
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

class NguoiDungImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $chucVu = ChucVuModel::where('ten_chuc_vu', trim($row['Chức vụ']))->first();

        if (!$chucVu) {
            throw ValidationException::withMessages([
                'Chức vụ' => "Chức vụ '{$row['Chức vụ']}' không tồn tại"
            ]);
        }

        return new NguoiDungModel([
            'ten_nguoi_dung' => $row['Tên người dùng'],
            'email' => $row["Email"],
            'mat_khau' => Hash::make($row['Mật khẩu']),
            'id_chuc_vu' => $chucVu->id,
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên người dùng' => 'required|string|max:255',
            '*.Email' => 'required|string|max:255|unique:nguoi_dung,email',
            '*.Mật khẩu' => 'required|min:6',
            '*.Chức vụ' => 'required|string',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Email.unique' => 'Email đã tồn tại trong hệ thống',
        ];
    }
}
