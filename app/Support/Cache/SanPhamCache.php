<?php

namespace App\Support\Cache;

use App\Models\SanPhamModel;
use Illuminate\Support\Facades\Cache;

class SanPhamCache
{
    //CACHE
    public static function detail(string $maSp): string
    {
        return "san_pham:$maSp";
    }

    public static function list(): string
    {
        return "danh_sach_san_pham";
    }

    //XÓA CACHE
    public static function forgetDetail(string $maSp): void
    {
        Cache::forget(self::detail($maSp));
    }

    public static function forgetList(): void
    {
        Cache::forget(self::list());
    }

    public static function forgetProduct(int $idSanPham): void
    {
        $maSps = SanPhamModel::find($idSanPham) ?->sanPhamChiTiets()->pluck('ma_sp');

        if (!$maSps) {
            return;
        }

        foreach ($maSps as $maSp) {
            self::forgetDetail($maSp);
        }
    }
}