<?php

namespace App\Services\MuaHangOnline;

use App\Models\SanPhamChiTietModel; 
use Illuminate\Http\Request;
use Exception;

class GioHangService 
{
    public function themVaoGioHang($request)
    {
        $sp = SanPhamChiTietModel::where('ma_sp', $request->ma_sp)->firstOrFail();

        $soLuongThem = $request->so_luong ?? 1;

        if ($soLuongThem > $sp->so_luong) {
            throw new Exception(
                //'Số lượng mua vượt quá tồn kho!'
                'Sản phẩm này chỉ còn lại ' .$sp->so_luong. ' sản phẩm'
            );
        }

        $cart = session()->get('cart', []);

        $soLuongHienTai = $cart[$sp->id]['so_luong'] ?? 0;

        $tongSoLuong = $soLuongHienTai + $soLuongThem;

        if ($tongSoLuong > $sp->so_luong) {
            throw new Exception(
                'Tổng số lượng trong giỏ vượt quá tồn kho!'
            );
        }

        $giaApDung = $sp->gia_khuyen_mai && 
        $sp->gia_khuyen_mai > 0 ? $sp->gia_khuyen_mai : $sp->gia_ban;

        $cart[$sp->id] = [
            'id' => $sp->id,
            'ma_sp' => $sp->ma_sp,
            'ten_san_pham' => $sp->sanPham->ten_san_pham,
            'ten_phu' => $sp->ten_phu,
            /*'gia_ban' => $sp->gia_ban,*/

            'gia_ap_dung' => $giaApDung,
            'gia_goc' => $sp->gia_ban,
            'gia_khuyen_mai' => $sp->gia_khuyen_mai,

            'so_luong' => $tongSoLuong,
            'ton_kho' => $sp->so_luong,
            'anh' => $sp->anh_dai_dien,
        ];

        session()->put('cart', $cart);
    }

    public function capNhatGioHang($request)
    {
        $cart = session()->get('cart', []);

        $id = (int) $request->id;

        if (!isset($cart[$id])) {
            throw new Exception('Sản phẩm không tồn tại!');
        }

        $sp = SanPhamChiTietModel::findOrFail($id);

        $soLuong = (int) $request->so_luong;

        if ($soLuong > $sp->so_luong) {
            throw new Exception('Số lượng vượt quá tồn kho!');
        }

        $cart[$id]['so_luong'] = $soLuong;

        session()->put('cart', $cart);
    }

    public function xoaGioHang($request)
    {
        $cart = session()->get('cart', []);

        unset($cart[$request->id]);

        session()->put('cart', $cart);
    }

}