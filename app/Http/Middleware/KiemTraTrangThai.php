<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KiemTraTrangThai
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();

            if ($user->trang_thai != 1) {
                Auth::guard('admin')->logout();
                return redirect('/login')
                    ->withErrors(['username' => 'Tài khoản đã bị khóa']);
            }
        }

        if (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user();

            if ($user->trang_thai != 1) {
                Auth::guard('customer')->logout();
                return redirect('/login')
                    ->withErrors(['username' => 'Tài khoản đã bị khóa']);
            }
        }

        return $next($request);
    }
}