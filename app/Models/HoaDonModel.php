<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use App\Models\Traits\DateFormat;

class HoaDonModel extends Authenticatable
{   
    //use DateFormat;

    protected $table = 'hoa_don';
    protected $fillable = ['id_khach_hang', 
                        'ma_hd', 
                        'dia_chi_hd',
                        'ten_nguoi_nhan',
                        'sdt_nguoi_nhan',
                        'tong_tien_goc',
                        'ten_giam_gia',
                        'giam_gia',
                        'loai_giam_gia_hd',
                        'tong_tien_thuc',
                        'loai_hinh',
                        'trang_thai_thanh_toan',
                        'trang_thai', 
                        'ngay_tao'
                    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->ma_hd)) {

                do {
                    $maHd = 'HD' . strtoupper(Str::random(7));
                } while (
                    self::where('ma_hd', $maHd)->exists()
                );

                $model->ma_hd = $maHd;
            }
        });
    }             

    protected $appends = ['ten_loai_hinh'];
    public function getTenLoaiHinhAttribute()
    {
        return match($this->loai_hinh) {
            0 => 'Thanh toán khi nhận',
            1 => 'Thanh toán trực tuyến',
            2 => 'Thanh toán bằng ví',
            default => 'Không xác định'
        };
    }

    public function khachhang(){
        return $this->belongsTo(KhachHangModel::class, 'id_khach_hang');
    }

    public function chiTiets()
    {
        return $this->hasMany(HoaDonCtModel::class, 'id_hoa_don');
    }

    public function trangthaihd()
    {
        return $this->belongsTo(TrangThaiHoaDonModel::class, 'trang_thai');
    }

    public function thoiGianTrangThai()
    {
        return $this->hasMany(ThoiGianTrangThaiModel::class, 'id_hoa_don', 'id');
    }

    

}