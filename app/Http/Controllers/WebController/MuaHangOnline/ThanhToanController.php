<?php

namespace App\Http\Controllers\WebController\MuaHangOnline;

use App\Http\Controllers\Controller;
use App\Services\MuaHangOnline\ThanhToanService;
use Illuminate\Http\Request;

class ThanhToanController extends Controller
{
    protected ThanhToanService $thanhToanService;

    public function __construct(ThanhToanService $thanhToanService)
    {
        $this->thanhToanService = $thanhToanService;
    }

    public function index() 
    {
        return view('thanh_toan.ThanhToan');
    }

    
}
