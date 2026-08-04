<?php

namespace App\Http\Controllers\AuthController\LichSuMuaHang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\LichSuMuaHang\LichSuMuaHangService;
use App\Models\TrangThaiHoaDonModel;

class LichSuMuaHangController extends Controller
{
    protected LichSuMuaHangService $lichSuMuaHangService;

    public function __construct(LichSuMuaHangService $lichSuMuaHangService)
    {
        $this->lichSuMuaHangService = $lichSuMuaHangService;
    }

    public function index(Request $request)
    {
        $perPage = 5; 

        $search = $request->input('search');
        $dsDonHang = $this->lichSuMuaHangService->getList($search, $perPage);  

        return view('lich_su_mua_hang.LichSuMuaHang', [ 
            'dsDonHang' => $dsDonHang,
            'showDetail' => false,
            'search' => $search
        ]);
    }

    public function xemHoaDon($id) 
    {
        $hoaDon = $this->lichSuMuaHangService->xemChiTiet($id);

        $hoaDonChiTiet = $this->lichSuMuaHangService->getHoaDonChiTiet($id);

        $trangThaiMoi = $this->lichSuMuaHangService->TrangThaiMoi($id); 

        return view('lich_su_mua_hang.ChiTietHoaDon', [
            'hoaDon' => $hoaDon,
            'hoaDonChiTiet' => $hoaDonChiTiet,
            'trangThaiMoi' => $trangThaiMoi,
        ]);
    }

}
