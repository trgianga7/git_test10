<?php

namespace App\Services\Auth;

use App\Models\KhachHangModel;
use Illuminate\Support\Facades\Hash;

class DangKyService
{
    public function dangKy($data)
    {
        return KhachHangModel::create([
            'sdt' => $data['sdt'],
            'ten_khach_hang' => $data['ten_khach_hang'],
            'mat_khau' => Hash::make($data['password']),
            'loai_khach_hang' => 1,
            'loai_tai_khoan' => 0,
            'trang_thai' => 1,
            'ngay_tao' => now()
        ]);
    }

}