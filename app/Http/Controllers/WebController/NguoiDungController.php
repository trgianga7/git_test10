<?php

namespace App\Http\Controllers\WebController;

use App\Models\NguoiDungModel;
use App\Services\NguoiDung\NguoiDungService;
use App\Http\Controllers\Controller;

class NguoiDungController extends Controller
{

    protected NguoiDungService $nguoiDungService;

    public function __construct(NguoiDungService $nguoiDungService)
    {
        $this->nguoiDungService = $nguoiDungService;
    }

    public function view()
    {
        return view('nguoi_dung.layout');
    }

    public function create()
    {
        return view('nguoi_dung.create');
    }

    public function edit($uuid)
    {
        return view('nguoi_dung.update', compact('uuid'));
    }

    public function mokhoa($id)
    {
        $this->nguoiDungService->mokhoa($id);

        return redirect()->route('nguoi-dung.index')
            ->with('success', 'Đã xóa thời gian khóa!');
    }

}
