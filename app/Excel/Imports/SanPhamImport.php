<?php

namespace App\Excel\Imports;

use App\Models\NguoiDungModel;
use App\Models\SanPhamModel;
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

class SanPhamImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        //dd($row);
        $nguoiDung = NguoiDungModel::where('ten_nguoi_dung', trim($row['Người tạo']))->first();
        $danhMuc = DanhMucModel::where('ten_danh_muc', trim($row['Tên danh mục']))->first();

        if (!$nguoiDung) {
            throw ValidationException::withMessages([
                'Người tạo' => "Người dùng '{$row['Người tạo']}' không tồn tại"
            ]);
        }
        if (!$danhMuc) {
            throw ValidationException::withMessages([
                'Tên danh mục' => "Danh mục '{$row['Tên danh mục']}' không tồn tại"
            ]);
        }

        return new SanPhamModel([
            'id_danh_muc' => $danhMuc->id,
            'ten_san_pham' => $row["Tên sản phẩm"],
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
            'nguoi_tao' => $nguoiDung->id,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên danh mục' => 'required|string|max:255',
            '*.Tên sản phẩm' => 'required|string|max:255|unique:san_pham,ten_san_pham',
            '*.Trạng thái' => 'nullable|in:0,1',
            '*.Người tạo' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Tên sản phẩm.unique' => 'Tên sản phẩm đã tồn tại trong hệ thống',
        ];
    }
}
