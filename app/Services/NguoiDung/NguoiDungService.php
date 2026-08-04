<?php

namespace App\Services\NguoiDung;

use App\Models\NguoiDungModel;
use Illuminate\Support\Facades\Log;

class NguoiDungService
{
    public function getList($search = null, $perPage = 5)
    {
        $query = NguoiDungModel::with('chucvu'); 

        if ($search) {
            $query->where('ten_nguoi_dung', 'like', "{$search}%")
                    ->orWhere('email', 'like', "{$search}%")
            ;
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function create($data)
    {
        $data['mat_khau'] = bcrypt($data['mat_khau']);
        $data['trang_thai'] = 1;
        $data['ngay_tao'] = now();

        $nguoiDung = NguoiDungModel::create($data);

        return $nguoiDung;
    }

    public function update($id, $data)
    {
        if (!empty($data['mat_khau'])) {
            $data['mat_khau'] = bcrypt($data['mat_khau']);
        } else {
            unset($data['mat_khau']);
        }

        $nguoiDung = NguoiDungModel::findOrFail($id); 
        $nguoiDung->update($data);

        return $nguoiDung;
    }

    public function getDetail($uuid)
    {
        return NguoiDungModel::with('chucvu')
                            ->where('uuid', $uuid)
                            ->firstOrFail();
    }

    public function delete($uuid)
    {
        $nguoiDung = $this->getDetail($uuid);
        return $nguoiDung->delete();
    }

    public function mokhoa($id)
    {
        $nguoiDung = NguoiDungModel::findOrFail($id);

        $nguoiDung->update([
            'so_lan_sai' => 0,
            'thoi_gian_khoa' => null
        ]);

        return $nguoiDung; 
    }
}