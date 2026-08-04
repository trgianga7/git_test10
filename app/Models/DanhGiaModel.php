<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DateFormat;
use App\Models\DanhGiaModel;

class DanhGiaModel extends Model
{
    use DateFormat;

    protected $table = 'danh_gia';
    protected $fillable = ['id_san_pham_chi_tiet',
                        'id_hoa_don_chi_tiet',
                        'ma_danh_gia',
                        'danh_gia', 
                        'noi_dung',
                        'id_khach_hang',
                        'trang_thai', 
                        'thoi_gian_danh_gia'];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

    public function khachHang()
    {
        return $this->belongsTo(KhachHangModel::class, 'id_khach_hang');
    }

    public function dinhKems()
    {
        return $this->hasMany(DinhKemDanhGiaModel::class, 'id_danh_gia');
    }

    public function sanPhamChiTiet(){
        return $this->belongsTo(SanPhamChiTietModel::class, 'id_san_pham_chi_tiet');
    }

}