<?php

namespace App\Excel\Imports;

use App\Models\HoaDonModel;
use App\Models\HoaDonCtModel;
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

class HoaDonChiTietImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        //dd($row);
        $hoaDon = HoaDonModel::where('ma_hd', trim($row['Mã HĐ']))->first();
        $sanPham = SanPhamModel::where('ten_san_pham', trim($row['Tên sản phẩm']))->first();
        $sanPhamChiTiet = SanPhamChiTietModel::where('ma_sp', trim($row['Mã SP']))->first();

        if (!$hoaDon) {
            throw ValidationException::withMessages([
                'Mã HĐ' => "Hóa đơn '{$row['Mã HĐ']}' không tồn tại"
            ]);
        }
        
        if (!$sanPham) {
            throw ValidationException::withMessages([
                'Mã SP' => "Sản phẩm có mã '{$row['Mã SP']}' không tồn tại"
            ]);
        }

        return new HoaDonCtModel([
            'id_hoa_don' => $hoaDon->id,
            'id_san_pham_chi_tiet' => $sanPhamChiTiet->id,
            'ten_san_pham' => $row['Tên sản phẩm'],
            'gia_ban' => $row['Giá bán'],
            'so_luong' => $row['Số lượng'],
            'tong_tien_hd' => $row['Tổng tiền hóa đơn'],
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Mã HĐ' => 'required|string|max:255',
            '*.Tên sản phẩm' => 'required|string|max:255',
            '*.Giá bán' => 'required|integer',
            '*.Số lượng' => 'required|integer',
            '*.Tổng tiền hóa đơn' => 'required|integer',
        ];
    }

    public function customValidationMessages()
    {
        return [
            
        ];
    }

}
