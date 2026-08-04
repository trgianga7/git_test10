<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuyenHan
{
    /*public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->loadMissing('chucvu');

        $quyenHan = $user->chucvu->quyen_han ?? null;

        if (!in_array($quyenHan, $roles)) {
            abort(403, "Bạn không có quyền truy cập trang này");
        }

        return $next($request);
    }*/
}
