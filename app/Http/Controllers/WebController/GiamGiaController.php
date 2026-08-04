<?php

namespace App\Http\Controllers\WebController;

use App\Services\GiamGia\GiamGiaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GiamGiaController extends Controller
{
    protected GiamGiaService $giamGiaService;

    public function __construct(GiamGiaService $giamGiaService)
    {
        $this->giamGiaService = $giamGiaService;
    }

    public function view()
    {
        return view('giam_gia.layout');
    }

    public function create()
    {
        return view('giam_gia.create');
    }

    public function edit($id)
    {
        return view('giam_gia.update', compact('id'));
    }

    public function create_sp_giam_gia()
    {
        return view('giam_gia.create_sanpham_giam');
    }

}
