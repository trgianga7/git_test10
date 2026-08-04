<?php

namespace App\Http\Controllers\ApiController;

use App\Services\KhachHang\KhachHangService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KhachHangApiController extends Controller
{
    protected KhachHangService $khachHangService;

    public function __construct(KhachHangService $khachHangService)
    {
        $this->khachHangService = $khachHangService;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->khachHangService->getList($request->search)
        );
    }

    public function getAll(Request $request)
    {
        return response()->json(
            $this->khachHangService->getListAll()
        );
    }

    public function show($uuid)
    {
        return response()->json(
            $this->khachHangService->getDetail($uuid)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loai_khach_hang' => 'required|integer|in:1,2',
            'ten_khach_hang' => 'required|string|max:255',
            'sdt' => 'required|unique:khach_hang,sdt|max:255',
            'mat_khau' => 'required|string|max:50'
        ]);

        $khachHang = $this->khachHangService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $khachHang
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $timKhachHang = $this->khachHangService->getDetail($uuid);

        $data = $request->validate([
            'loai_khach_hang' => 'required|integer|in:1,2',
            'ten_khach_hang' => 'required|string|max:255',
            'sdt' => 'required|max:255|unique:khach_hang,sdt,'. $timKhachHang->id,
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $khachHang = $this->khachHangService->update($timKhachHang->id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $khachHang
        ]);
    }

    public function destroy($uuid)
    {
        $this->khachHangService->delete($uuid);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

}