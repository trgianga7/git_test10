<?php

namespace App\Services\ThongKe;

use App\Models\HoaDonModel;
use App\Models\HoaDonCtModel;
use Illuminate\Support\Facades\DB;


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

    public function donHangChuaHoanThanh()
    {
        return HoaDonModel::where('trang_thai', '!=', 5)
            ->count();
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

    public function top10KhachHang()
    {
        return DB::table('hoa_don')
            ->join('khach_hang',
                'hoa_don.id_khach_hang', '=', 'khach_hang.id')
            ->select(
                'hoa_don.id_khach_hang',
                'khach_hang.ten_khach_hang',
                'khach_hang.sdt'
            )
            ->selectRaw('SUM(hoa_don.tong_tien_thuc) AS tong_chi_tieu')
            ->selectRaw('COUNT(hoa_don.id) AS tong_don_hang')
            ->where('hoa_don.trang_thai', 5)
            ->groupBy(
                'hoa_don.id_khach_hang',
                'khach_hang.ten_khach_hang',
                'khach_hang.sdt'
            )
            ->orderByDesc('tong_chi_tieu')
            ->limit(10)
            ->get();
    }

    public function doanhThuTheoNgay()
    {
        //return HoaDonModel::selectRaw('DATE(ngay_tao) as ngay, SUM(tong_tien_thuc) as doanh_thu')
        return DB::table('hoa_don')
            ->selectRaw('DATE(ngay_tao) as ngay, SUM(tong_tien_thuc) as doanh_thu')
            ->where('trang_thai', 5)
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->get();
    }

    public function doanhThuTheoThang()
    {
        //return HoaDonModel::selectRaw('YEAR(ngay_tao) as nam, MONTH(ngay_tao) as thang, SUM(tong_tien_thuc) as doanh_thu')
        return DB::table('hoa_don')
            ->selectRaw('YEAR(ngay_tao) as nam, MONTH(ngay_tao) as thang, SUM(tong_tien_thuc) as doanh_thu')
            ->where('trang_thai', 5)
            ->groupBy('nam','thang')
            ->orderBy('nam')
            ->orderBy('thang')
            ->get();
    }

    public function doanhThuTheoNam()
    {
        //return HoaDonModel::selectRaw('YEAR(ngay_tao) as nam, SUM(tong_tien_thuc) as doanh_thu')
        return DB::table('hoa_don')   
            ->selectRaw('YEAR(ngay_tao) as nam, SUM(tong_tien_thuc) as doanh_thu') 
            ->where('trang_thai', 5)
            ->groupBy('nam')
            ->orderBy('nam')
            ->get();
    }
}