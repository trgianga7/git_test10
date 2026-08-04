<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DiaChi\DiaChiService;

class DiaChiController extends Controller
{
    protected DiaChiService $diaChiService;

    public function __construct(DiaChiService $diaChiService)
    {
        $this->diaChiService = $diaChiService;
    }

    public function view(Request $request)
    {
        return view('dia_chi.layout');
    }

    public function create()
    {
        return view('dia_chi.create');
    }

    public function edit($id)
    {
        return view('dia_chi.update', [
            'id' => $id
        ]);
    }
}
