<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KiemTraQuyen
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('admin')->user();

        if (!$user) {
            abort(403);
        }

        if ($user->chucVu && $user->chucVu->ten_chuc_vu === 'Admin') {
            return $next($request);
        }

        $routeName = $request->route()->getName();

        if (!$routeName) {
            return $next($request);
        }

        $chucVu = $user->chucvu;

        if (!$chucVu) {
            abort(403, 'Tài khoản chưa có chức vụ');
        }

        $coQuyen = $chucVu->chucNangs()
            ->where('route', $routeName)
            ->wherePivot('trang_thai', 1)
            ->exists();

        if (!$coQuyen) {
            abort(403, 'Bạn không có quyền truy cập chức năng này');
        }

        return $next($request);
    }
}
