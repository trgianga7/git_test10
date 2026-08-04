<?php

namespace App\Services\ThongKe;

use App\Models\HoaDonModel;
use App\Models\HoaDonCtModel;


class ThongKeService
{
    public function thongKe($search = null, $perPage = 5)
    {
         
    }

    public function tongDoanhThu()
    {
        return HoaDonModel::where('trang_thai', 5)
        ->sum('tong_tien_thuc');
    }

    public function tongDonHang()
    {
        return HoaDonModel::where('trang_thai', 5)
            ->count();
    }

    public function tongSanPham()
    {
        return HoaDonCtModel::whereHas('hoadon', function ($query) {
            $query->where('trang_thai', 5);
        })
        ->sum('so_luong');
    }

    public function sanPhamBanChay()
    {
        return HoaDonCtModel::select('ten_san_pham')
        ->selectRaw('SUM(so_luong) as tong')
        ->whereHas('hoadon', function ($q) {
            $q->where('trang_thai', 5);
        })
        ->groupBy('ten_san_pham')
        ->orderByDesc('tong')
        ->first();
    }

    public function chiPhi()
    {
        return HoaDonCtModel::join('hoa_don', 'hoa_don_chi_tiet.id_hoa_don', '=', 'hoa_don.id')
            ->join('san_pham_chi_tiet', 'hoa_don_chi_tiet.id_san_pham_chi_tiet', '=', 'san_pham_chi_tiet.id')
            ->where('hoa_don.trang_thai', 5)
            ->selectRaw('SUM(san_pham_chi_tiet.gia_ban * hoa_don_chi_tiet.so_luong) as tong_chi_phi')
            ->value('tong_chi_phi');
    }

    public function top10SanPham()
    {
        return HoaDonCtModel::select('ten_san_pham')
            ->selectRaw('SUM(so_luong) as tong_da_ban')
            ->whereHas('hoadon', function ($q) {
                $q->where('trang_thai', 5);
            })
            ->groupBy('ten_san_pham')
            ->orderByDesc('tong_da_ban')
            ->limit(10)
            ->get();
    }

    public function doanhThuTheoNgay()
    {
        return HoaDonModel::selectRaw('DATE(ngay_tao) as ngay, SUM(tong_tien_thuc) as doanh_thu')
            ->where('trang_thai', 5)
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->get();
    }

    public function doanhThuTheoThang()
    {
        return HoaDonModel::selectRaw('YEAR(ngay_tao) as nam, MONTH(ngay_tao) as thang, SUM(tong_tien_thuc) as doanh_thu')
            ->where('trang_thai', 5)
            ->groupBy('nam','thang')
            ->orderBy('nam')
            ->orderBy('thang')
            ->get();
    }

    public function doanhThuTheoNam()
    {
        return HoaDonModel::selectRaw('YEAR(ngay_tao) as nam, SUM(tong_tien_thuc) as doanh_thu')
            ->where('trang_thai', 5)
            ->groupBy('nam')
            ->orderBy('nam')
            ->get();
    }
}