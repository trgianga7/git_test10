<?php

namespace App\Excel\Imports;

use App\Models\DanhMucModel;
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

class DanhMucImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new DanhMucModel([
            'ten_danh_muc' => $row['Tên danh mục'],
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên danh mục' => 'required|string|max:255',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Tên danh mục.unique' => 'Danh mục đã tồn tại trong hệ thống',
        ];
    }
}
