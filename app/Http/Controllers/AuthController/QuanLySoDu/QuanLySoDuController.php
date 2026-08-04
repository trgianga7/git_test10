<?php

namespace App\Http\Controllers\AuthController\QuanLySoDu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\QuanLySoDu\QuanLySoDuService;

class QuanLySoDuController extends Controller
{
    protected QuanLySoDuService $quanLySoDuService;

    public function __construct(QuanLySoDuService $quanLySoDuService)
    {
        $this->quanLySoDuService = $quanLySoDuService;
    }

    public function xemSoDu(Request $request)
    {
        $khachHang = $this->quanLySoDuService->thongTin();

        return view('quan_ly_so_du.QuanLySoDu', [ 
            'khachHang' => $khachHang
        ]);
    }

}
