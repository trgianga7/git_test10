<?php

namespace App\Services\Auth;

use App\Models\KhachHangModel;
use App\Models\DiaChiModel;
use App\Models\APIModel\TinhModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuanLyDiaChiService
{
    public function thongTinKhachHang()
    {
        return Auth::guard('customer')->user();
    }

    public function themDiaChiCaNhan($data)
    {
        return DiaChiModel::create([
            'id_khach_hang' => $this->thongTinKhachHang()->id,
            'tinh' => $data['tinh'],
            'huyen' => $data['huyen'],
            'phuong' => $data['phuong'],
            'dia_chi' => $data['dia_chi'],
            'trang_thai' => 1,
            'ngay_tao' => now(),
        ]);
    }

    public function diaChiFindTheoKhachHang($id)
    {
        return DiaChiModel::where('id', $id)
            ->where('id_khach_hang', $this->thongTinKhachHang()->id)
            ->firstOrFail();
    }

    public function tatCaDiaChiKhachHang()
    {
        return DiaChiModel::where('id_khach_hang', $this->thongTinKhachHang()->id)->get();
    }

    public function tinh()
    {
        return TinhModel::all();
    }

    public function update($id, $data)
    {
        $diaChi = $this->diaChiFindTheoKhachHang($id);
        return $diaChi->update($data);
    }

    public function delete($id)
    {
        $diaChi = $this->diaChiFindTheoKhachHang($id);
        return $diaChi->delete($id);
    }
}