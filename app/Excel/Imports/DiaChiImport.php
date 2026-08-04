<?php

namespace App\Excel\Imports;

use App\Models\DiaChiModel;
use App\Models\KhachHangModel;
use App\Models\APIModel\TinhModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Traits\RutGonDiaChi;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class DiaChiImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;
    use RutGonDiaChi;

    public function model(array $row)
    {
        $khachHang = KhachHangModel::where('ten_khach_hang', trim($row['Tên khách hàng']))->first();
        if (!$khachHang) {
            throw ValidationException::withMessages([
                'id_khach_hang' => "Khách hàng '{$row['Tên khách hàng']}' không tồn tại"
            ]);
        }

        $tinh = TinhModel::where('province_name', trim($row['Tỉnh']))->first();
        if (!$tinh) {
            throw ValidationException::withMessages([
                'Tỉnh' => "Không tìm thấy tỉnh: {$row['Tỉnh']}"
            ]);
        }

        $huyenNhap = $this->rutGonDiaChi($row['Huyện']);

        $huyen = HuyenModel::where('province_id', $tinh->province_id)
            ->get()
            ->first(function ($h) use ($huyenNhap) {
                return $this->rutGonDiaChi($h->district_name) === $huyenNhap;
            });
        if (!$huyen) {
            throw ValidationException::withMessages([
                'Huyện' => "Không tìm thấy huyện '{$row['Huyện']}' thuộc tỉnh '{$row['Tỉnh']}'"
            ]);
        }

        $phuongNhap = $this->rutGonDiaChi($row['Phường']);

        $phuong = PhuongModel::where('district_id', $huyen->district_id)
            ->get()
            ->first(function ($p) use ($phuongNhap) {
                return $this->rutGonDiaChi($p->ward_name) === $phuongNhap;
            });
        if (!$phuong) {
            throw ValidationException::withMessages([
                'Phường' => "Không tìm thấy phường '{$row['Phường']}' thuộc huyện '{$row['Huyện']}'"
            ]);
        }

        return new DiaChiModel([
            'id_khach_hang' => $khachHang->id,
            'tinh' => $tinh->province_id,
            'huyen' => $huyen->district_id,
            'phuong' => $phuong->ward_code,
            'dia_chi' => $row['Địa chỉ'],
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(), 
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Tên khách hàng' => 'required|string|max:255',
            '*.Tỉnh' => 'required|string',
            '*.Huyện' => 'required|string',
            '*.Phường' => 'required|string',
            '*.Địa chỉ' => 'required|string',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [

        ];
    }
}
