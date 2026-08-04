<?php

namespace App\Excel\Imports;

use App\Models\HoaDonModel;
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

class HoaDonImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        //dd($row);
        $khachHang = KhachHangModel::where('ten_khach_hang', trim($row['Tên khách hàng']))->first();

        if (!$khachHang) {
            throw ValidationException::withMessages([
                'Tên khách hàng' => "Khách hàng '{$row['Tên khách hàng']}' không tồn tại"
            ]);
        }

        return new HoaDonModel([
            'id_khach_hang' => $khachHang->id,
            'dia_chi_hd' => $row['Địa chỉ hóa đơn'],
            'tong_tien_goc' => $row['Tổng tiền gốc'],
            'giam_gia' => $row['Giảm giá'] ?? 0,
            'tong_tien_thuc' => $row['Tổng tiền thực'],
            'loai_hinh' => $row['Loại hình'],
            'ghi_chu' => $row['Ghi chú'] ?? 'Không có',
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên khách hàng' => 'required|string|max:255',
            '*.Địa chỉ hóa đơn' => 'required|string|max:255',
            //'*.Tổng tiền gốc' => 'required|integer',
            '*.Giảm giá' => 'nullable|integer',
            '*.Tổng tiền thực' => 'required|integer',
            '*.Loại hình' => 'required|in:0,1',
            '*.Ghi chú' => 'nullable|string',
            '*.Trạng thái' => 'nullable|in:1,2,3,4,5',
        ];
    }

    public function customValidationMessages()
    {
        return [
            
        ];
    }

}
