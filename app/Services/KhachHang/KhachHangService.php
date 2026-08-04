<?php

namespace App\Services\KhachHang;

use App\Models\KhachHangModel;

class KhachHangService
{
    public function getList($search = null, $perPage = 5) 
    {
        $query = KhachHangModel::where('loai_khach_hang', '!=', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ten_khach_hang', 'like', "{$search}%")
                  ->orWhere('sdt', 'like', "{$search}%");
            });
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function getListAll()
    {
        return KhachHangModel::where('loai_khach_hang', '!=', 0)
                            ->where('trang_thai', '!=', 0)
                            ->orderBy('ten_khach_hang')->get();
    }

    public function create($data)
    {
        $data['mat_khau'] = bcrypt($data['mat_khau']);
        $data['loai_tai_khoan'] = 0;
        $data['trang_thai'] = 1;
        $data['ngay_tao'] = now();

        $khachHang = KhachHangModel::create($data);

        return $khachHang;
    }

    public function update($id, $data)
    {
        if (!empty($data['mat_khau'])) {
            $data['mat_khau'] = bcrypt($data['mat_khau']);
        } else {
            unset($data['mat_khau']);
        }

        $khachHang = KhachHangModel::findOrFail($id); 
        $khachHang->update($data);

        return $khachHang;
    }

    public function getDetail($uuid)
    {
        return KhachHangModel::where('uuid', $uuid)
                            ->firstOrFail();
    }

    public function delete($uuid)
    {
        $khachHang = $this->getDetail($uuid);
        return $khachHang->delete();
    }

    public function mokhoa($id)
    {
        $khachHang = KhachHangModel::findOrFail($id);

        $khachHang->update([
            'so_lan_sai' => 0,
            'thoi_gian_khoa' => null
        ]);

        return $khachHang;
    }
}