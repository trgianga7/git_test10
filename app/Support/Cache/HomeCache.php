<?php

namespace App\Support\Cache;

use Illuminate\Support\Facades\Cache;

class HomeCache
{
    //CACHE
    public static function category(): string
    {
        return "home:danh_muc";
    }

    public static function reviewSummary(int $idSanPham): string
    {
        return "tong_danh_gia:$idSanPham";
    }

    //XÓA CACHE
    public static function forgetCategory(): void
    {
        Cache::forget(self::category());
    }

    public static function forgetReviewSummary(int $idSanPham): void
    {
        Cache::forget(self::reviewSummary($idSanPham));
    }
}