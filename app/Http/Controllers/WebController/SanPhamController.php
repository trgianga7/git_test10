<?php

namespace App\Http\Controllers\WebController;

use App\Services\SanPham\SanPhamService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SanPhamController extends Controller
{
    protected SanPhamService $sanPhamService;

    public function __construct(SanPhamService $sanPhamService)
    {
        $this->sanPhamService = $sanPhamService;
    }

    public function view()
    {
        return view('san_pham.layout');
    }

    public function create()
    {
        return view('san_pham.create');
    }

    public function edit($key_sp)
    {
        return view('san_pham.update', compact('key_sp'));
    }
}
