<?php

namespace App\Services\GiamGia;

use App\Models\GiamGiaModel;
use App\Models\SanPhamChiTietModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GiamGiaService
{
    //Giảm theo mã
    public function getList($search = null, $perPage = 5)
    {
        $query = GiamGiaModel::query();

        if ($search) {
            $query->where('ma_giam_gia', 'like', "{$search}%");
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function create($data)
    {
        if (isset($data['ngay_het_han']) && $data['ngay_het_han'] < now()) {
            throw ValidationException::withMessages([
                'ngay_het_han' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày hiện tại.'
            ]);
        }

        $data['trang_thai'] = 1;
        $data['ngay_tao'] = now();

        $giamGia = GiamGiaModel::create($data);

        return $giamGia;
    }

    public function update($id, $data)
    {
        if (isset($data['ngay_het_han']) && $data['ngay_het_han'] < now()) {
            throw ValidationException::withMessages([
                'ngay_het_han' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày hiện tại.'
            ]);
        }

        $giamGia = GiamGiaModel::findOrFail($id);
        $giamGia->update($data);

        return $giamGia;
    }

    public function getDetail($id)
    {
        return GiamGiaModel::findOrFail($id);
    }

    public function delete($id)
    {
        $giamGia = GiamGiaModel::findOrFail($id);
        return $giamGia->delete();
    }

    //Giảm theo sản phẩm
    public function sanPhamGiamGia($search = null, $perPage = 10)
    {
        return SanPhamChiTietModel::with([
            'sanpham'
        ])
        
        ->whereNotNull('gia_khuyen_mai')
        ->where('gia_khuyen_mai', '>', 0)
        ->whereColumn('gia_khuyen_mai', '<', 'gia_ban')
        
        ->when($search, function ($q) use ($search) {
        
            $q->whereHas('sanpham', function ($sp) use ($search) {
        
                $sp->where(
                    'ten_san_pham',
                    'like',
                    "{$search}%"
                );
            });
        })
        
        ->paginate($perPage);
    }

    public function themKhuyenMai($data)
    {
        $spct = SanPhamChiTietModel::findOrFail($data['spct_id']);
    
        if( $data['gia_khuyen_mai'] >= $spct->gia_ban)
        {
            throw ValidationException::withMessages([
                'gia_khuyen_mai' => 'Giá khuyến mãi phải nhỏ hơn giá bán'
            ]);
        }
    
        $spct->update(['gia_khuyen_mai' => $data['gia_khuyen_mai']]);
    }

    public function huyKhuyenMai($id)
    {
        $spct = SanPhamChiTietModel::findOrFail($id);

        $spct->update([
            'gia_khuyen_mai' => null
        ]);

        return true;
    }
}