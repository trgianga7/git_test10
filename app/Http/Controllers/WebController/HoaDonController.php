<?php

namespace App\Http\Controllers\WebController;

use App\Services\HoaDon\HoaDonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HoaDonController extends Controller 
{
    protected HoaDonService $hoaDonService;

    public function __construct(HoaDonService $hoaDonService)
    {
        $this->hoaDonService = $hoaDonService;
    }

    public function view()
    {
        return view('hoa_don.layout');
    }

    public function create()
    {
        return view('hoa_don.create');
    }

    public function edit($ma_hd)
    {
        return view('hoa_don.update', compact('ma_hd'));
    }

    public function viewInfo($ma_hd)
    {
        return view('hoa_don.view', compact('ma_hd'));
    }
}
