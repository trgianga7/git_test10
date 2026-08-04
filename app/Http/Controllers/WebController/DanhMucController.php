<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use App\Services\DanhMuc\DanhMucService;
use Illuminate\Http\Request;

class DanhMucController extends Controller 
{

    protected DanhMucService $danhMucService;

    public function __construct(DanhMucService $danhMucService)
    {
        $this->danhMucService = $danhMucService;
    }

    public function view(Request $request)
    {
        return view('danh_muc.layout');
    }

    public function create()
    {
        return view('danh_muc.create');
    }

    public function edit($id)
    {
        return view('danh_muc.update', [
            'id' => $id
        ]);
    }
}
