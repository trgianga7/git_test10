<?php

namespace App\Http\Controllers\ApiController;

use App\Services\SanPham\SanPhamService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuanLyResource\SanPham\SanPhamResource;
use App\Http\Resources\QuanLyResource\SanPham\SanPhamSelectResource;

class SanPhamApiController extends Controller
{
    protected SanPhamService $sanPhamService;

    public function __construct(SanPhamService $sanPhamService)
    {
        $this->sanPhamService = $sanPhamService;
    }

    public function index(Request $request)
    {
        /*return response()->json(
            $this->sanPhamService->getList($request->search)
        );*/
        $sanPham = $this->sanPhamService->getList($request->search);

        return response()->json([
            'data' => SanPhamResource::collection($sanPham),
            'current_page' => $sanPham->currentPage(),
            'last_page' => $sanPham->lastPage(),
            'per_page' => $sanPham->perPage(),
            'total' => $sanPham->total()
        ]);
    }

    public function getAll(Request $request)
    {
        /*return response()->json(
            $this->sanPhamService->getListAll()
        );*/
        $sanPhamHoatDong = $this->sanPhamService->getListAll($request);

        return response()->json(
            SanPhamSelectResource::collection($sanPhamHoatDong)
        );
    }

    public function show($key_sp)
    {
        return response()->json(
            $this->sanPhamService->getDetail($key_sp)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_danh_muc' => 'required',
            'ten_san_pham' => 'required|unique:san_pham,ten_san_pham',
        ]);

        $sanPham = $this->sanPhamService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $sanPham
        ]);
    }

    public function update(Request $request, $key_sp)
    {
        $timSanPham = $this->sanPhamService->getDetail($key_sp);

        $data = $request->validate([
            'id_danh_muc' => 'required',
            'ten_san_pham' => 'required|unique:san_pham,ten_san_pham,' .$timSanPham->id,
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $sanPham = $this->sanPhamService->update(
            $timSanPham->id, 
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $sanPham
        ]);
    }

    public function destroy($key_sp)
    {
        $this->sanPhamService->delete($key_sp);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

}