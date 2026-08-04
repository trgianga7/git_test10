<?php

namespace App\Services\DiaChi;

use App\Models\DiaChiModel;
use App\Models\KhachHangModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use App\Models\APIModel\TinhModel;

class DiaChiService
{

    public function getList($search = null, $perPage = 5)
    {
        $query = DiaChiModel::with([
            'khachhang',
            'tinh_ten',
            'huyen_ten',
            'phuong_ten'
        ]); 

        if ($search) {
            $query->where('dia_chi', 'like', "{$search}%")
                    ->orWhereHas('khachhang', function ($kh) use ($search) {
                        $kh->where('ten_khach_hang', 'like', "{$search}%");
                    });
            ;
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function getKhachHang()
    {
        return KhachHangModel::where('trang_thai', 1)
                            ->where('loai_khach_hang' ,'!=', 0)
                            ->get();
    }

    public function getTinh()
    {
        $tinh = TinhModel::orderBy('province_name')->get();
        return $tinh;
    }

    public function getHuyen($province_id)
    {
        $huyen = HuyenModel::where('province_id', $province_id)
                            ->select('district_id', 'district_name')
                            ->get();
        return $huyen;
    }

    public function getPhuong($district_id)
    {
        $phuong = PhuongModel::where('district_id', $district_id)
                            ->select('ward_code', 'ward_name')
                            ->get();
        return $phuong;
    }

    public function create($data)
    {
        $data['trang_thai'] = 1;
        $data['ngay_tao'] = now();

        $diaChi = DiaChiModel::create($data);

        return $diaChi;
    }

    public function update($id, $data)
    {
        $diaChi = DiaChiModel::findOrFail($id); 
        $diaChi->update($data);

        return $diaChi;
    }

    public function getDetail($id)
    {
        return DiaChiModel::findOrFail($id);
    }

    public function delete($id)
    {
        $diaChi = DiaChiModel::findOrFail($id);
        return $diaChi->delete();
    }
}