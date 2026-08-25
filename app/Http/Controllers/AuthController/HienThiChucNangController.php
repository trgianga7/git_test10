<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HienThiChucNangController extends Controller
{
    public static function getMenu()
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return self::tatCaFalse();
        }

        // Admin thấy tất cả
        if (
            $user->chucVu &&
            $user->chucVu->ten_chuc_vu === 'Admin'
        ) {
            return self::tatCaTrue();
        }

        $permissions = $user->chucVu
            ->chucNangs()
            ->wherePivot('trang_thai', 1)
            ->pluck('route')
            ->toArray();

        return [
            'nguoi_dung'         => in_array('nguoi-dung.index', $permissions),
            'chuc_vu'            => in_array('chuc-vu.index', $permissions),
            'dia_chi'            => in_array('dia-chi.index', $permissions),
            'khach_hang'         => in_array('khach-hang.index', $permissions),
            'danh_muc'           => in_array('danh-muc.index', $permissions),
            'san_pham'           => in_array('san-pham.index', $permissions),
            'san_pham_chi_tiet'  => in_array('san-pham-chi-tiet.index', $permissions),
            'hoa_don'            => in_array('hoa-don.index', $permissions),
            'giam_gia'           => in_array('giam-gia.index', $permissions),
            'thong_ke'           => in_array('thong-ke.index', $permissions),
        ];
    }

    private static function tatCaTrue()
    {
        return [
            'nguoi_dung'        => true,
            'chuc_vu'           => true,
            'dia_chi'           => true,
            'khach_hang'        => true,
            'danh_muc'          => true,
            'san_pham'          => true,
            'san_pham_chi_tiet' => true,
            'hoa_don'           => true,
            'giam_gia'          => true,
            'thong_ke'          => true,
        ];
    }

    private static function tatCaFalse()
    {
        return [
            'nguoi_dung'        => false,
            'chuc_vu'           => false,
            'dia_chi'           => false,
            'khach_hang'        => false,
            'danh_muc'          => false,
            'san_pham'          => false,
            'san_pham_chi_tiet' => false,
            'hoa_don'           => false,
            'giam_gia'          => false,
            'thong_ke'          => false,
        ];
    }
}