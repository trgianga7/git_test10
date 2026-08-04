<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ThongKe\ThongKeService;

class ThongKeApiController extends Controller
{
    protected ThongKeService $thongKeService;

    public function __construct(ThongKeService $thongKeService)
    {
        $this->thongKeService = $thongKeService;
    }

    public function index()
    {
        $tongDoanhThu = $this->thongKeService->tongDoanhThu();

        $chiPhi = $this->thongKeService->chiPhi();

        return response()->json([
            'tongDoanhThu' => $tongDoanhThu,
            'tongDonHang' => $this->thongKeService->tongDonHang(),
            'tongSanPham' => $this->thongKeService->tongSanPham(),
            'sanPhamBanChay' => $this->thongKeService->sanPhamBanChay(),
            'chiPhi' => $chiPhi,
            'loiNhuan' => $tongDoanhThu - $chiPhi,
            'top10SanPham' => $this->thongKeService->top10SanPham(),
            'doanhThuTheoNgay' => $this->thongKeService->doanhThuTheoNgay(),
            'doanhThuTheoThang' => $this->thongKeService->doanhThuTheoThang(),
            'doanhThuTheoNam' => $this->thongKeService->doanhThuTheoNam()
        ]);
    }
}