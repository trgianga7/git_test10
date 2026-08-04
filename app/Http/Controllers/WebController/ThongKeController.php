<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ThongKeController extends Controller 
{

    public function thong_ke()
    {
        return view('thong_ke.layout');
    }

}
