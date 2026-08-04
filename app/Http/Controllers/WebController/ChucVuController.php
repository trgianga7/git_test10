<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChucVuController extends Controller
{
    public function view()
    {
        return view('chuc_vu.layout');
    }

    public function create()
    {
        return view('chuc_vu.create');
    }

    public function edit($id)
    {
        return view('chuc_vu.update', [
            'id' => $id
        ]);
    }
}