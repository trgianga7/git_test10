<?php

namespace App\Http\Controllers\ApiController;

use App\Services\SanPhamChiTiet\SanPhamChiTietService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuanLyResource\SanPhamChiTiet\SanPhamChiTietResource;
use App\Http\Resources\QuanLyResource\SanPhamChiTiet\SanPhamChiTietSelectResource;

class SanPhamChiTietApiController extends Controller
{
    protected SanPhamChiTietService $sanPhamChiTietService;

    public function __construct(SanPhamChiTietService $sanPhamChiTietService)
    {
        $this->sanPhamChiTietService = $sanPhamChiTietService;
    }

    public function index(Request $request)
    {
        /*return response()->json(
            $this->sanPhamChiTietService->getList($request->search)
        );*/

        $sanPhamCt = $this->sanPhamChiTietService->getList($request->search);

        return response()->json([
            'data' => SanPhamChiTietResource::collection($sanPhamCt),
            'current_page' => $sanPhamCt->currentPage(),
            'last_page' => $sanPhamCt->lastPage(),
            'per_page' => $sanPhamCt->perPage(),
            'total' => $sanPhamCt->total()
        ]);
    }

    public function getAll(Request $request)
    {
        /*return response()->json(
            $this->sanPhamChiTietService->getListAll()
        );*/
        $sanPhamCtHoatDong = $this->sanPhamChiTietService->getListAll($request);

        return response()->json(
            SanPhamChiTietSelectResource::collection($sanPhamCtHoatDong)
        );
    }

    public function show($ma_sp)
    {
        return response()->json(
            $this->sanPhamChiTietService->getDetail($ma_sp)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_san_pham' => 'required|int',
            'ten_phu' => 'required|string|max:255',
            //'gia_goc' => 'required|numeric|min:0',
            'gia_ban' => 'required|numeric|min:0',
            'so_luong' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string|max:2000',
            'anh_dai_dien' => 'nullable|image|file',
            'anh.*' => 'nullable|image'
        ]);

        $sanPhamCt = $this->sanPhamChiTietService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $sanPhamCt
        ]);
    }

    public function update(Request $request, $ma_sp)
    {
        $timSanPhamCt = $this->sanPhamChiTietService->getDetail($ma_sp);

        $data = $request->validate([
            'id_san_pham' => 'required|int',
            'ten_phu' => 'required|string|max:255',
            //'gia_goc' => 'required|numeric|min:0',
            'gia_ban' => 'required|numeric|min:0',
            'so_luong' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string|max:2000',
            'trang_thai' => 'required|integer|in:0,1',
            'anh_dai_dien' => 'nullable|image',
            'anh.*' => 'nullable|image'
        ]);

        $sanPhamCt = $this->sanPhamChiTietService->update(
            $timSanPhamCt->id, 
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $sanPhamCt
        ]);
    }

    public function destroy($ma_sp)
    {
        $this->sanPhamChiTietService->delete($ma_sp);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

}