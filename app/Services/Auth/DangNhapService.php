<?php

namespace App\Services\Auth;

use App\Models\KhachHangModel;
use App\Models\NguoiDungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DangNhapService
{
    public function admin($username)
    {
        return NguoiDungModel::where('email', $username)->first();
    }

    public function customer($username)
    {
        return KhachHangModel::where('sdt', $username)->first();
    }
    
    public function login($username, $password)
    {
        $admin = $this->admin($username);

        if ($admin) {
            return $this->dangNhapNguoiDung($admin, $password);
        }

        $customer = $this->customer($username);

        if ($customer) {
            return $this->dangNhapKhachHang($customer, $password);
        }

        throw ValidationException::withMessages([
            'username' => 'Thông tin đăng nhập không đúng'
        ]);
    }

    public function xuLySaiMatKhau($user)
    {
        $user->so_lan_sai++;

        if ($user->so_lan_sai >= 5) {

            $user->update([
                'thoi_gian_khoa' => now()->addMinutes(30),
                'so_lan_sai' => 0
            ]);

            throw ValidationException::withMessages([
                'username' => 'Bạn đã nhập sai quá 5 lần. Tài khoản bị khóa 30 phút.'
            ]);
        }

        $user->save();

        $conLai = 5 - $user->so_lan_sai;

        throw ValidationException::withMessages([
            'username' => "Sai mật khẩu. Bạn còn {$conLai} lần thử."
        ]);
    }

    public function dangNhapNguoiDung($admin, $password)
    {
        if ($admin->thoi_gian_khoa && now()->lt($admin->thoi_gian_khoa)) {

            $thoiGianKhoa = now()->diffInMinutes($admin->thoi_gian_khoa);

            throw ValidationException::withMessages([
                'username' => "Tài khoản bị khóa. Vui lòng thử lại sau {$thoiGianKhoa} phút."
            ]);
        }

        if ($admin->trang_thai == 0) {
            throw ValidationException::withMessages([
                'username' => 'Tài khoản đã bị khóa!',
            ]);
        }

        if (Hash::check($password, $admin->mat_khau)) {

            $admin->update([
                'so_lan_sai' => 0,
                'thoi_gian_khoa' => null
            ]);

            Auth::guard('customer')->logout();
            Auth::guard('admin')->login($admin);

            return ['redirect' => '/quan-ly'];
        }

        return $this->xuLySaiMatKhau($admin);
    }

    public function dangNhapKhachHang($customer, $password)
    {
        if ($customer->thoi_gian_khoa && now()->lt($customer->thoi_gian_khoa)) {

            $thoiGianKhoa = now()->diffInMinutes($customer->thoi_gian_khoa);

            throw ValidationException::withMessages([
                'username' => "Tài khoản bị khóa. Vui lòng thử lại sau {$thoiGianKhoa} phút."
            ]);
        }

        if ($customer->trang_thai == 0) {
            throw ValidationException::withMessages([
                'username' => 'Tài khoản đã bị khóa!',
            ]);
        }

        if (Hash::check($password, $customer->mat_khau)) {

            $customer->update([
                'so_lan_sai' => 0,
                'thoi_gian_khoa' => null
            ]);

            Auth::guard('admin')->logout();
            Auth::guard('customer')->login($customer);

            return [
                'redirect' => route('trang-chu.Home')
            ];
        }

        return $this->xuLySaiMatKhau($customer);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('dang-nhap')
            ->with('success', 'Đã đăng xuất!');
    }

}