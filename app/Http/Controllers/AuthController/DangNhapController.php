<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use App\Services\Auth\DangNhapService; 
//use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class DangNhapController extends Controller
{
    protected DangNhapService $dangNhapService;

    public function __construct(DangNhapService $dangNhapService)
    {
        $this->dangNhapService = $dangNhapService;
    }

    public function showLogin()
    {
        return view('auth.dang_nhap');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $ketQua = $this->dangNhapService->login(
            $request->username,
            $request->password
        );

        if ($ketQua instanceof RedirectResponse) {
            return $ketQua;
        }

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'redirect' => $ketQua['redirect']
        ]);
    }

    public function logout(Request $request)
    {
        return $this->dangNhapService->logout($request);
    }

}