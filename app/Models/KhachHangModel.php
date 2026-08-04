<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use App\Models\Traits\DateFormat;

class KhachHangModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'khach_hang';
    protected $fillable = [ 'uuid',
                            'ten_khach_hang', 
                            'loai_khach_hang', 
                            'anh_dai_dien',
                            'sdt', 
                            'mat_khau',
                            'loai_tai_khoan',
                            'trang_thai', 
                            'so_lan_con_lai',
                            'thoi_gian_khoa',
                            'diem',
                            'sdt_moi',
                            'ngay_tao'];

    public $timestamps = false;

    protected $hidden = ['mat_khau'];
    public function getAuthPassword(){
        return $this->mat_khau;
    }

    protected $appends = ['ten_loai_khach_hang'];

    public function getTenLoaiKhachHangAttribute(){
        return match ($this->loai_khach_hang) {
            1 => 'Khách thường',
            2 => 'Khách đặc biệt',
            default => 'Khách ẩn danh',
        };
    }

    public function diaChis()
    {
        return $this->hasMany(DiaChiModel::class, 'id_khach_hang');
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