<?php

namespace App\Services\Auth\LichSuMuaHang;

use App\Models\HoaDonModel; 
use App\Models\KhachHangModel;
use App\Models\HoaDonCtModel;
use App\Models\SanPhamChiTietModel;
use App\Models\TrangThaiHoaDonModel;
use App\Models\DanhGiaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LichSuMuaHangService
{
    public function getList($search = null, $perPage = 5) 
    {
        $khachHangId = auth('customer')->id();
        $query = HoaDonModel::query()
        ->select('hoa_don.*', 'khach_hang.ten_khach_hang')
        ->join('khach_hang', 'hoa_don.id_khach_hang', '=', 'khach_hang.id')
        ->where('hoa_don.id_khach_hang', $khachHangId);
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('hoa_don.ma_hd', 'like', "{$search}%");
                  //->orWhere('hoa_don.ma_hd', 'like', "{$search}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();;
    }

    public function xemChiTiet($id)
    {
        return HoaDonModel::query()
            ->select('hoa_don.*', 'khach_hang.ten_khach_hang')
            ->join('khach_hang', 'hoa_don.id_khach_hang', '=', 'khach_hang.id')
            ->where('hoa_don.id', $id)
            ->firstOrFail();
    }

    public function getHoaDonChiTiet($id)
    {
        return HoaDonCtModel::where('id_hoa_don', $id)->get();
    }

    public function getListTrangThai()
    {
        return TrangThaiHoaDonModel::all();
    }
    
    public function TrangThaiMoi($id)
    {
        $trangThaiMoi = $hoaDon = HoaDonModel::findOrFail($id);

        return $trangThaiMoi->thoiGianTrangThai()->orderBy('thoi_gian_trang_thai', 'desc')->first();    

    }

}