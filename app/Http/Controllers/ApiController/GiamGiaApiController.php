<?php

namespace App\Http\Controllers\ApiController;

use App\Services\GiamGia\GiamGiaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuanLyResource\GiamGia\GiamGiaSanPhamResource;

class GiamGiaApiController extends Controller
{
    protected GiamGiaService $giamGiaService;

    public function __construct(GiamGiaService $giamGiaService)
    {
        $this->giamGiaService = $giamGiaService;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->giamGiaService->getList($request->search)
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->giamGiaService->getDetail($id)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_giam_gia' => 'required|string|max:255|unique:giam_gia,ten_giam_gia',
            'loai_giam_gia' => 'required|in:0,1',
            'ma_giam_gia' => 'required|string|max:50|unique:giam_gia,ma_giam_gia',
            'gia_tri' => 'required|integer',
            'so_luong' => 'required|integer',
            'ngay_bat_dau' => 'required',
            'ngay_het_han' => 'required'
        ]);

        $giamGia = $this->giamGiaService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $giamGia
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'ten_giam_gia' => 'required|string|max:255|unique:giam_gia,ten_giam_gia,' .$id,
            'loai_giam_gia' => 'required|in:0,1',
            'ma_giam_gia' => 'required|string|max:50|unique:giam_gia,ma_giam_gia,' .$id,
            'gia_tri' => 'required|integer',
            'so_luong' => 'required|integer',
            'ngay_bat_dau' => 'required',
            'ngay_het_han' => 'required',
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $giamGia = $this->giamGiaService->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $giamGia
        ]);
    }

    public function destroy($id)
    {
        $this->giamGiaService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

    //Giảm giá theo sản phẩm
    public function sanPhamGiamGia(Request $request)
    {
        /*return response()->json(

            $this->giamGiaService->sanPhamGiamGia(
                    $request->search
                )

        );*/
        $giamGiaSp = $this->giamGiaService->sanPhamGiamGia($request->search);

        return response()->json([
            'data' => GiamGiaSanPhamResource::collection($giamGiaSp),
            'current_page' => $giamGiaSp->currentPage(),
            'last_page' => $giamGiaSp->lastPage(),
            'per_page' => $giamGiaSp->perPage(),
            'total' => $giamGiaSp->total()
        ]);
    }

    public function themSanPhamGiamGia(Request $request)
    {
        $data = $request->validate([
            'spct_id' => 'required|exists:san_pham_chi_tiet,id',
            'gia_khuyen_mai' => 'required|integer|min:1'
        ]);

        $spct = $this->giamGiaService->themKhuyenMai($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm khuyến mãi thành công',
            'data' => $spct
        ]);
    }    

    public function huySanPhamGiamGia($id)
    {
        $this->giamGiaService->huyKhuyenMai($id);

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy khuyến mãi'
        ]);
    }

}