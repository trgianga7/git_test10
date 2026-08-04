<?php

namespace App\Http\Controllers\ApiController;

use App\Services\MuaHangOnline\ThanhToanService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThanhToanApiController extends Controller
{
    protected ThanhToanService $thanhToanService;

    public function __construct(ThanhToanService $thanhToanService)
    {
        $this->thanhToanService = $thanhToanService;
    }

    public function index()
    {
        return response()->json(
            $this->thanhToanService->hienThiThanhToan()
        );
    }

    public function thanhToan(Request $request)
    {
        $hoaDon = $this->thanhToanService->thanhToan($request);

        return response()->json([
            'message' => 'Đặt hàng thành công',
            'hoa_don_id' => $hoaDon->id
        ]);
    }

    public function kiemTraMa(Request $request)
    {
        return response()->json(
            $this->thanhToanService
                ->kiemTraMa($request)
        );
    }

    public function taoQR($id)
    {
        return response()->json(
            $this->thanhToanService->taoQR($id)
        );
    }

}