<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Services\Auth\DangKyService; 
use Illuminate\Http\Request;

class DangKyController extends Controller
{

    protected DangKyService $dangKyService;

    public function __construct(DangKyService $dangKyService)
    {
        $this->dangKyService = $dangKyService;
    }

    public function showForm()
    {
        return view('auth.dang_ky');
    }


    public function register(Request $request)
    {
        $request->merge([
            'sdt' => trim($request->sdt),
            'ten_khach_hang' => trim($request->ten_khach_hang),
        ]);
        
        $data = $request->validate([
            //'sdt' => 'required|digits_between:9,15|unique:khach_hang,sdt',
            'sdt' => 'required|unique:khach_hang,sdt',
            'ten_khach_hang' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:255|confirmed',
        ]);

        $this->dangKyService->dangKy($data);

        return response()->json([
            'message' => 'Đăng ký thành công! Vui lòng đăng nhập.',
            'redirect' => route('dang-nhap')
        ]);
    }
}
