<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use App\Models\Traits\DateFormat;

class SanPhamChiTietModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'san_pham_chi_tiet';
    protected $fillable = ['id_san_pham', 
                            'ma_sp', 
                            'anh_dai_dien',
                            'ten_phu',
                            'mo_ta',
                            'gia_ban',
                            'gia_khuyen_mai',
                            'khuyen_mai',
                            'so_luong',
                            //'id_nguoi_dung',
                            'trang_thai', 
                            'ngay_tao'
                        ];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

    public function sanpham(){
        return $this->belongsTo(SanPhamModel::class, 'id_san_pham');
    }

    public function nguoiban(){
        return $this->belongsTo(NguoiDungModel::class, 'id_nguoi_dung');
    }

    public function hinhAnhs()
    {
        return $this->hasMany(HinhAnhModel::class, 'id_san_pham_chi_tiet');
    }

    protected $appends = ['nhieu_mo_ta'];
    public function getNhieuMoTaAttribute()
    {
        return array_filter(array_map('trim', explode('|', $this->mo_ta)));
    }

    /*public function getGiaGocAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }

    public function getGiaBanAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }*/

    /*protected static function booted()
    {
        static::created(function ($model) {
            if (empty($model->ma_sp)) {
                $model->update([
                    'ma_sp' => 'SP' . $model->id
                ]);
            }
        });
    }*/
    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->ma_sp)) {
                $model->ma_sp = (string) Str::uuid();
            }

        });
    }
}