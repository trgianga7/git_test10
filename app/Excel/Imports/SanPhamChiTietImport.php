<?php

namespace App\Excel\Imports;

use App\Models\SanPhamModel;
use App\Models\SanPhamChiTietModel;
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

class SanPhamChiTietImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        //dd($row);
        $sanPham = SanPhamModel::where('ten_san_pham', trim($row['Tên sản phẩm']))->first();

        if (!$sanPham) {
            throw ValidationException::withMessages([
                'Tên sản phẩm' => "Sản phẩm '{$row['Tên sản phẩm']}' không tồn tại"
            ]);
        }

        return new SanPhamChiTietModel([
            //'ma_sp' => 1,
            'id_san_pham' => $sanPham->id,
            'thong_tin_1' => $row['Thông tin 1'],
            'thong_tin_2' => $row['Thông tin 2'],
            'gia_goc' => $row['Giá gốc'],
            'gia_ban' => $row['Giá bán'],
            'so_luong' => $row['Số lượng'] ?? 1,
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên sản phẩm' => 'required|string|max:255',
            '*.Thông tin 1' => 'required|string|max:255',
            '*.Thông tin 2' => 'required|string|max:255',
            '*.Giá gốc' => 'required|integer',
            '*.Giá bán' => 'required|integer',
            '*.Số lượng' => 'required|integer',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Mã sản phẩm.unique' => 'Mã sản phẩm đã tồn tại trong hệ thống',
        ];
    }

}
