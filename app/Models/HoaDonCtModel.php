<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\DateFormat;

class HoaDonCtModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'hoa_don_chi_tiet';
    protected $fillable = ['id_hoa_don', 
                        'id_san_pham_chi_tiet', 
                        'ten_san_pham',
                        'gia_ban',
                        'giam_gia_sp',
                        'so_luong',
                        'tong_tien_hd',
                        'ngay_tao'
                    ];

    public $timestamps = false;

    public function hoadon(){
        return $this->belongsTo(HoaDonModel::class, 'id_hoa_don');
    }

    public function sanPhamChiTiet()
    {
        return $this->belongsTo(SanPhamChiTietModel::class, 'id_san_pham_chi_tiet');
    }

    public function danhGia()
    {
        return $this->hasOne(DanhGiaModel::class, 'id_hoa_don_chi_tiet');
    }

}