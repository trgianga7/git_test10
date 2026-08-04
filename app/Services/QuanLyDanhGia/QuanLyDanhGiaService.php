<?php

namespace App\Services\QuanLyDanhGia;

use App\Models\DanhGiaModel;
 
class QuanLyDanhGiaService 
{
    public function getList($search = null, $perPage = 5)
    {
        $query = DanhGiaModel::query();

        if ($search) {
            $query->where('ma_danh_gia', 'like', "{$search}%")
                    ->orWhere('noi_dung', 'like', "{$search}%");
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function update($id)
    {
        $danhGia = $this->getDetail($id)->first();

        /*if (!$danhGia) {
            return redirect()->back()->with('error', 'Không tìm thấy đánh giá');
        }*/

        $danhGia->trang_thai = $danhGia->trang_thai == 1 ? 0 : 1;

        $danhGia->save();

        return $danhGia;
    }

    public function getDetail($id)
    {
        return DanhGiaModel::findOrFail($id);
    }

    public function delete($danhGia)
    {
        return $danhGia->delete();
    }
}