<?php

namespace App\Http\Controllers\WebController;

use App\Services\KhachHang\KhachHangService;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;

class KhachHangController extends Controller
{

    protected KhachHangService $khachHangService;

    public function __construct(KhachHangService $khachHangService)
    {
        $this->khachHangService = $khachHangService;
    }

    public function view(Request $request)
    {
        return view('khach_hang.layout');
    }

    public function create()
    {
        return view('khach_hang.create');
    }
    
    public function edit($uuid)
    {
        return view('khach_hang.update', compact('uuid'));
    }

    public function mokhoa($id)
    {
        $this->khachHangService->mokhoa($id);

        return redirect()->route('khach_hang.layout')
            ->with('success', 'Đã xóa thời gian khóa!');
    }
}
