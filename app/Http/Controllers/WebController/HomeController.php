<?php

namespace App\Http\Controllers\WebController;

use App\Services\HomeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    protected HomeService $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function view()
    {
        return view('trang_chu.Home');
    }

    public function menuSanPham()
    {
        return view('trang_chu.SanPham');
    }

    public function chiTietSanPham($ma_sp)
    {
        return view('trang_chu.ChiTiet', compact('ma_sp'));
    }

    /*public function chiTiet($id)
    {
        $sanPhamCt = $this->homeService->sanPhamChiTiet($id);
        $dsDanhGia = $this->homeService->danhSachDanhGia($id);
        $dsDaMua = $this->homeService->daMuaChuaDanhGia($id);

        return view('trang_chu.ChiTiet', compact('sanPhamCt', 'dsDanhGia', 'dsDaMua'));
    }

    public function muaNgay(Request $request)
    {
        $request->validate([ 
            'id_san_pham_chi_tiet' => 'required|integer',
            'so_luong' => 'required|integer|min:1'
        ]);

        $this->homeService->muaNgay($request); 

        return redirect()->route('thanh-toan.index');
    }

    public function themDanhGia(Request $request)
    {   
        //dd($request); 
        $request->validate([
            'danh_gia' => 'required|numeric|min:1|max:5|regex:/^[1-5](\.5)?$/',
            'noi_dung' => 'nullable|string|max:1000',
    
            'images' => 'nullable|array|max:5',
    
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120' 
        ], [
            'images.max' => 'Chỉ được upload tối đa 5 ảnh',
            'images.*.max' => 'Mỗi ảnh tối đa 5MB',
            'images.*.image' => 'File phải là hình ảnh',
        ]);
        $result = $this->homeService->themDanhGia($request);

        if (!$result['status']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }*/
}
