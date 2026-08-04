<?php

namespace App\Services\Auth\QuanLySoDu;

use App\Models\KhachHangModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class QuanLySoDuService
{

    public function thongTin()
    {
        return Auth::guard('customer')->user();
    }

}