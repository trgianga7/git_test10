<?php

namespace App\Services\DanhMuc;

use App\Models\DanhMucModel;
use Illuminate\Support\Facades\Cache;

class DanhMucService 
{
    public function getList($search = null, $perPage = 5)
    {
        $query = DanhMucModel::query();

        if ($search) {
            $query->where('ten_danh_muc', 'like', "{$search}%");
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function getListAll()
    {
        return DanhMucModel::where('trang_thai', '!=', 0)
                            ->orderBy('ten_danh_muc')->get();
    }

    public function create($data)
    {
        $data['trang_thai'] = 1;
        $data['ngay_tao'] = now();

        $danhMuc = DanhMucModel::create($data);

        Cache::forget('home:danh_muc');

        return $danhMuc;
    }

    public function update($id, $data)
    {
        $danhMuc = DanhMucModel::findOrFail($id);
        $danhMuc->update($data);

        Cache::forget('home:danh_muc');

        return $danhMuc;
    }

    public function getDetail($id)
    {
        return DanhMucModel::findOrFail($id);
    }

    public function delete($id)
    {
        $danhMuc = DanhMucModel::findOrFail($id);

        $delete = $danhMuc->delete();

        Cache::forget('home:danh_muc');

        return $delete;
    }

}