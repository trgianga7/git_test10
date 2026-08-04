<?php

namespace App\Http\Controllers\ApiController;

use App\Services\MuaHangOnline\GioHangService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GioHangApiController extends Controller
{
    protected GioHangService $gioHangService;

    public function __construct(GioHangService $gioHangService)
    {
        $this->gioHangService = $gioHangService;
    }

    public function soLuong()
    {
        $cart = session('cart', []);

        return response()->json([
            'so_luong' => collect($cart)->sum('so_luong')
        ]);
    }

    public function index()
    {
        $cart = array_values(session('cart', []));

        return response()->json($cart);
    }

    public function them(Request $request)
    {
        $this->gioHangService->themVaoGioHang($request);

        return response()->json([
            'message' => 'Đã thêm vào giỏ hàng'
        ]);
    }

    public function capNhat(Request $request)
    {
        $this->gioHangService->capNhatGioHang($request);

        return response()->json([
            'message' => 'Đã cập nhật'
        ]);
    }

    public function xoa(Request $request)
    {
        $this->gioHangService->xoaGioHang($request);

        return response()->json([
            'message' => 'Đã xóa'
        ]);
    }

}