<?php

namespace App\Services\SanPham;

use App\Models\SanPhamModel;
use App\Models\DanhMucModel;
use App\Support\Cache\SanPhamCache;

class SanPhamService
{
    public function getList($search = null, $perPage = 5)
    {
        $query = SanPhamModel::with([
            'nguoidung',
            'danhmuc' 
        ]);

        if ($search) { 
            $query->where('ten_san_pham', 'like', "{$search}%");
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function getListAll()
    {
        return SanPhamModel::where('trang_thai', '!=', 0)
                            ->orderBy('ten_san_pham')->get();
    }

    public function getDanhMuc()
    {
        return DanhMucModel::where('trang_thai', 1)->get();
    }

    public function create($data)
    {
        $data['trang_thai'] = 1;
        $data['nguoi_tao'] = auth('admin')->id();
        //$data['nguoi_tao'] = 1;
        $data['ngay_tao'] = now();

        $sanPham = SanPhamModel::create($data);

        

        return $sanPham;
    }

    public function update($id, $data)
    {
        $sanPham = SanPhamModel::findOrFail($id); 
        $sanPham->update($data);

        SanPhamCache::forgetProduct($sanPham->id);

        return $sanPham;
    }

    /*public function getDetail($key_sp)
    {
        return SanPhamModel::with('danhmuc')
                            ->where('key_sp', $key_sp)
                            ->firstOrFail();
    }*/
    public function getDetail($key_sp)
    {
        return SanPhamModel::with(['danhmuc', 'sanPhamChiTiets'])
                            ->where('key_sp', $key_sp)
                            ->firstOrFail();
    }

    public function delete($key_sp)
    {
        $sanPham = $this->getDetail($key_sp);

        SanPhamCache::forgetProduct($sanPham->id);

        return $sanPham->delete();
    }

}