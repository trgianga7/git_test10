<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use App\Models\Traits\DateFormat;


class NguoiDungModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'nguoi_dung';
    protected $fillable = [ 'uuid',
                            'id_chuc_vu', 
                            'ten_nguoi_dung', 
                            'anh_dai_dien',
                            'email', 
                            'mat_khau',
                            'trang_thai', 
                            'so_lan_sai',
                            'thoi_gian_khoa',
                            'diem',
                            'sdt_lien_he',
                            'ngay_tao'];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

    protected $hidden = ['mat_khau'];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function chucvu(){
        return $this->belongsTo(ChucVuModel::class, 'id_chuc_vu');
    }

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

        });
    }

}