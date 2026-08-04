<?php

namespace App\Services\Auth;

use App\Models\NguoiDungModel;
use App\Models\KhachHangModel;
use App\Models\DiaChiModel;
use App\Models\APIModel\TinhModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuanLyThongTinService
{
    public function thongTinNguoiDung()
    {
        return Auth::guard('admin')->user();
    }

    public function thongTinKhachHang()
    {
        return Auth::guard('customer')->user();
    }

    public function tatCaDiaChiKhachHang()
    {
        return DiaChiModel::where('id_khach_hang', $this->thongTinKhachHang()->id)->get();
    }

    public function tinh()
    {
        return TinhModel::all();
    }

    public function timNguoiDungBangId($id)
    {
        return NguoiDungModel::findOrFail($id);
    }

    public function timKhachHangBangId($id)
    {
        return KhachHangModel::findOrFail($id);
    }

    public function updateNguoiDung($id, $data)
    {
        $admin = $this->timNguoiDungBangId($id);

        $admin->ten_nguoi_dung = $data['ten_nguoi_dung'];
        $admin->sdt_lien_he = $data['sdt_lien_he'];

        if(!empty($data['mat_khau'])){
            $admin->mat_khau = bcrypt($data['mat_khau']);
        }

        if (!empty($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
            if ($admin->anh_dai_dien) {
                Storage::disk('public')->delete($admin->anh_dai_dien);
            }

            $extension = $data['anh_dai_dien']->getClientOriginalExtension();
            $fileName = 'AnhDaiDien_' . Str::random(5) . '.' . $extension;

            $path = $data['anh_dai_dien']->storeAs('anh_dai_dien', $fileName, 'public');
            $admin->anh_dai_dien = $path;
        }

        $admin->save();

    }

    public function updateKhachHang($id, $data)
    {
        $customer = $this->timKhachHangBangId($id);

        $customer->ten_khach_hang = $data['ten_khach_hang'];
        $customer->sdt_moi = $data['sdt_moi'];

        if(!empty($data['mat_khau'])){
            $customer->mat_khau = bcrypt($data['mat_khau']);
        }

        if (!empty($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
            if ($customer->anh_dai_dien) {
                Storage::disk('public')->delete($customer->anh_dai_dien);
            }

            $extension = $data['anh_dai_dien']->getClientOriginalExtension();
            $fileName = 'AnhDaiDien_' . Str::random(5) . '.' . $extension;

            $path = $data['anh_dai_dien']->storeAs('anh_dai_dien', $fileName, 'public');
            $customer->anh_dai_dien = $path;
        }

        $customer->save();

    }
}