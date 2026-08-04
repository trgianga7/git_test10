<?php

namespace App\Http\Controllers\WebController\MuaHangOnline;

use App\Http\Controllers\Controller;
use App\Services\MuaHangOnline\GioHangService;

class GioHangController extends Controller
{
    protected GioHangService $gioHangService;

    public function __construct(GioHangService $gioHangService)
    {
        $this->gioHangService = $gioHangService;
    }

    public function index()
    {
        return view('thanh_toan.GioHang');
    }


}
