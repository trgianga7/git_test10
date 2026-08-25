<?php

namespace App\Services\MuaHangOnline;

use App\Models\SanPhamChiTietModel;
use Exception;

class MuaNgayService
{
    public function muaNgay($request)
    {
        $sp = SanPhamChiTietModel::where('ma_sp',$request->ma_sp)
                                 ->firstOrFail();

        $soLuong = (int)$request->so_luong;

        if($soLuong <= 0){

            throw new Exception(
                'Số lượng không hợp lệ'
            );

        }

        if($soLuong > $sp->so_luong){

            throw new Exception(
                'Sản phẩm không đủ tồn kho'
            );

        }

        $giaApDung =
            $sp->gia_khuyen_mai &&
            $sp->gia_khuyen_mai > 0
                ? $sp->gia_khuyen_mai
                : $sp->gia_ban;

        session()->put(
            'buy_now',
            [

                [
                    'id' => $sp->id,
                    'ma_sp' => $sp->ma_sp,
                    'ten_san_pham' => $sp->sanPham->ten_san_pham,
                    'ten_phu' => $sp->ten_phu,
                    'gia_ap_dung' => $giaApDung,
                    'gia_goc' => $sp->gia_ban,
                    'gia_khuyen_mai' => $sp->gia_khuyen_mai,
                    'so_luong' => $soLuong,
                    'ton_kho' => $sp->so_luong,
                    'anh' => $sp->anh_dai_dien
                ]

            ]
        );
    }
}