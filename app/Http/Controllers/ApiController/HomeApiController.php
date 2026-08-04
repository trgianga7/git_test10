<?php

namespace App\Http\Controllers\ApiController;

use App\Services\HomeService;
use App\Services\MuaHangOnline\MuaNgayService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrangChuResource\ChiTietSanPham\ChiTietSanPhamResource;
use App\Http\Resources\TrangChuResource\ChiTietSanPham\DanhGiaResource;
use App\Http\Resources\TrangChuResource\ChiTietSanPham\HoaDonChiTietResource;
use App\Http\Resources\TrangChuResource\DanhSachSanPham\DanhMucSanPhamResource;
use App\Http\Resources\TrangChuResource\DanhSachSanPham\DanhSachSanPhamResource;


class HomeApiController extends Controller
{
    protected HomeService $homeService;
    protected MuaNgayService $muaNgayService;

    public function __construct(HomeService $homeService, MuaNgayService $muaNgayService)
    {
        $this->homeService = $homeService;
        $this->muaNgayService = $muaNgayService;
    }

    public function danhSachSanPham(Request $request)
    {
        /*return response()->json(
            $this->homeService->TimSanPham($request)
        );*/
        $dsSanPham = $this->homeService->TimSanPham($request);

        return response()->json([
            'data' => DanhSachSanPhamResource::collection($dsSanPham),
            'current_page' => $dsSanPham->currentPage(),
            'last_page' => $dsSanPham->lastPage(),
            'per_page' => $dsSanPham->perPage(),
            'total' => $dsSanPham->total()
        ]);
    }

    public function danhSachDanhMuc()
    {
        /*return response()->json(
            $this->homeService->tatCaDanhMuc()
        );*/
        return DanhMucSanPhamResource::collection(
            $this->homeService->tatCaDanhMuc()
        );
    }

    public function chiTietSanPham(Request $request, $ma_sp)
    {
        $sanPham = $this->homeService->sanPhamChiTiet($ma_sp);

        $danhGia = $this->homeService->danhSachDanhGia($sanPham->id);
        //$danhGia = $this->homeService->danhSachDanhGia($sanPham->id, $request->page ?? 1);

        $daMua = auth('customer')->check() ? $this->homeService->daMuaChuaDanhGia($sanPham->id) : [];

        //$summary = $this->homeService->reviewSummary($sanPham->id);
        $summary = $this->homeService->reviewSummary($sanPham->id_san_pham);

        /*return response()->json([
            'san_pham' => $sanPham,
            'danh_gia' => $danhGia,
            'da_mua'   => $daMua
        ]);*/
        //dd(DanhGiaResource::collection($danhGia));
        return response()->json([

            'san_pham' => new ChiTietSanPhamResource($sanPham),
        
            //'danh_gia' => DanhGiaResource::collection($danhGia),
            'danh_gia' => [
                'data' => DanhGiaResource::collection($danhGia->items()),
                'current_page' => $danhGia->currentPage(),
                'last_page' => $danhGia->lastPage(),
                'per_page' => $danhGia->perPage(),
                'total' => $danhGia->total(),
                
                'summary' => [
                    'count' => (int) $summary->total,
                    'average' => (float) ($summary->average ?? 0),
                    'star' => [
                        5 => (int) $summary->star5,
                        4 => (int) $summary->star4,
                        3 => (int) $summary->star3,
                        2 => (int) $summary->star2,
                        1 => (int) $summary->star1,
                    ],
                ],

            ],
        
            'da_mua' => HoaDonChiTietResource::collection($daMua)
        
        ]);
    }

    public function muaNgay(Request $request)
    {
        $this->muaNgayService->muaNgay($request);

        return response()->json([
            'message' => 'OK'
        ]);
    }

    public function postDanhGia(Request $request)
    {
        $request->validate([
            'id_hoa_don_chi_tiet' => 'required|integer|exists:hoa_don_chi_tiet,id',
            'danh_gia' => ['required', 'numeric', 'in:0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',],
            'noi_dung' => 'required|string|min:10|max:255',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            
        ],
        [
            'id_hoa_don_chi_tiet.required' => 'Không tìm thấy sản phẩm cần đánh giá.',
            'id_hoa_don_chi_tiet.integer'  => 'Dữ liệu sản phẩm không hợp lệ.',
            'id_hoa_don_chi_tiet.exists'   => 'Sản phẩm không tồn tại.',

            'danh_gia.required' => 'Vui lòng chọn số sao đánh giá.',
            'danh_gia.numeric'  => 'Điểm đánh giá không hợp lệ.',
            'danh_gia.in'       => 'Điểm đánh giá phải từ 0.5 đến 5 sao.',

            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noi_dung.string'   => 'Nội dung đánh giá không hợp lệ.',
            'noi_dung.min'      => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
            'noi_dung.max'      => 'Nội dung đánh giá chỉ được tối đa 255 ký tự.',

            'images.array'      => 'Danh sách ảnh không hợp lệ.',
            'images.max'        => 'Bạn chỉ được tải lên tối đa 5 ảnh.',

            'images.*.image'    => 'Mỗi tệp tải lên phải là hình ảnh.',
            'images.*.mimes'    => 'Ảnh chỉ hỗ trợ định dạng JPG, JPEG, PNG hoặc WEBP.',
            'images.*.max'      => 'Mỗi ảnh chỉ được tối đa 2MB.',
        ]
        );

        return response()->json(
            $this->homeService->themDanhGia($request)
        );
    }


}