<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Services\Auth\QuanLyThongTinService; 
use Illuminate\Http\Request;

class QuanLyThongTinController extends Controller
{
    protected QuanLyThongTinService $quanLyThongTinService;

    public function __construct(QuanLyThongTinService $quanLyThongTinService)
    {
        $this->quanLyThongTinService = $quanLyThongTinService;
    }

    public function index()
    {   
        session(['previous_url' => url()->previous()]);
        $admin = $this->quanLyThongTinService->thongTinNguoiDung();
        //return view('auth.quan_ly_thong_tin', compact('admin')); 
        return view('auth.layout_thong_tin', compact(
            'admin',
        ));
    }

    public function indexCustomer(Request $request)
    {
        session(['previous_url' => url()->previous()]);
        $customer = $this->quanLyThongTinService->thongTinKhachHang();
        $diaChis = $this->quanLyThongTinService->tatCaDiaChiKhachHang();
        $showAdd = $request->query('them_dia_chi') == 1;
        $tinhs = $this->quanLyThongTinService->tinh(); 

        /*return view('auth.quan_ly_thong_tin', 
            compact('customer', 
                    'diaChis'
            ));*/
        return view('auth.layout_thong_tin', 
            compact('customer',
                    'diaChis',
                    'showAdd',
                    'tinhs'
            ));
    }

    public function updateAdmin(Request $request)
    {
        //dd($request->file('anh_dai_dien'));
        $data = $request->validate([
            'ten_nguoi_dung' => 'required|string|max:255',
            'anh_dai_dien'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sdt_lien_he' => 'nullable|string|max:10',
            'mat_khau' => 'nullable|string|min:6|max:50',
        ]);

        $this->quanLyThongTinService->updateNguoiDung($request->id, $data);

        return back()->with('success', 'Cập nhật thông tin thành công!');
        //$url = session('previous_url', route('trang_chu.Home'));
        //return redirect($url)->with('success', 'Cập nhật thông tin thành công!');
    }

    public function updateCustomer(Request $request)
    {
        $data = $request->validate([
            'ten_khach_hang' => 'required|string|max:255',
            'anh_dai_dien'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sdt_moi' => 'nullable|string|max:10',
            'mat_khau' => 'nullable|string|min:6|max:50',
        ]);

        $this->quanLyThongTinService->updateKhachHang($request->id, $data);

        //$url = session('previous_url', route('trang_chu.Home'));
        //return redirect($url)->with('success', 'Cập nhật thông tin thành công!');
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function danhSachCaNhan()
    {
        $admin = $this->quanLyThongTinService->thongTinNguoiDung();
        $customer = $this->quanLyThongTinService->thongTinKhachHang();

        return view('auth.danh_sach_ca_nhan', compact('admin','customer'));
    }
}