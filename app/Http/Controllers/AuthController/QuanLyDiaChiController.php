<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Services\Auth\QuanLyDiaChiService; 
use Illuminate\Http\Request;

class QuanLyDiaChiController extends Controller
{
    protected QuanLyDiaChiService $quanLyDiaChiService;

    public function __construct(QuanLyDiaChiService $quanLyDiaChiService)
    {
        $this->quanLyDiaChiService = $quanLyDiaChiService;
    }

    public function store(Request $request)
    {
        //$customer = $this->quanLyDiaChiService->thongTinKhachHang();

        $data = $request->validate([
            'tinh' => 'required|integer',
            'huyen' => 'required|integer',
            'phuong' => 'required|string',
            'dia_chi' => 'required|string|max:255',
        ]);

        $this->quanLyDiaChiService->themDiaChiCaNhan($data);

        return back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function edit($id)
    {
        $customer = $this->quanLyDiaChiService->thongTinKhachHang();

        $diaChi = $this->quanLyDiaChiService->diaChiFindTheoKhachHang($id);

        $diaChis = $this->quanLyDiaChiService->tatCaDiaChiKhachHang();

        $tinhs = $this->quanLyDiaChiService->tinh();

        return view('auth.layout_thong_tin', [
            'customer' => $customer,
            'diaChis' => $diaChis,
            'tinhs' => $tinhs,
            'diaChiEdit' => $diaChi,
            'showEdit' => true,
            'showAdd' => false
        ]);
    }

    public function update(Request $request, $id)
    {
        //$customer = $this->quanLyDiaChiService->thongTinKhachHang();

        //$diaChi = $this->quanLyDiaChiService->diaChiFindTheoKhachHang($id);

        $data = $request->validate([
            'tinh' => 'required|integer',
            'huyen' => 'required|integer',
            'phuong' => 'required|string',
            'dia_chi' => 'required|string'
        ]);

        $this->quanLyDiaChiService->update($id, $data);

        return redirect()->route('quan_ly_thong_tin_customer')->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function delete($id)
    {
        //$customer = $this->quanLyDiaChiService->thongTinKhachHang();

        //$diaChi = $this->quanLyDiaChiService->diaChiFindTheoKhachHang($id);

        $this->quanLyDiaChiService->delete($id);

        return back()->with('success', 'Xóa địa chỉ thành công!');
    }
}