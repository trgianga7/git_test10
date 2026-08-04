<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use App\Models\Traits\DateFormat;

class SanPhamModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'san_pham';
    protected $fillable = [ 'key_sp',
                            'id_danh_muc', 
                            'ten_san_pham', 
                            'trang_thai',
                            'nguoi_tao',
                            'ngay_tao'];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->key_sp)) {
                $model->key_sp = (string) Str::uuid();
            }

        });
    }

    public function danhmuc(){
        return $this->belongsTo(DanhMucModel::class, 'id_danh_muc');
    }

    public function nguoidung(){
        return $this->belongsTo(NguoiDungModel::class, 'nguoi_tao');
    }

    public function sanPhamChiTiets(){
        return $this->hasMany(SanPhamChiTietModel::class, 'id_san_pham');
    }

}