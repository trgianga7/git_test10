<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\APIModel\TinhModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use App\Models\Traits\DateFormat;

//class DiaChiModel extends Authenticatable
class DiaChiModel extends Model
{   
    use DateFormat;

    protected $table = 'dia_chi';
    protected $fillable = ['id_khach_hang', 
                            'tinh', 
                            'huyen',
                            'phuong', 
                            'dia_chi',
                            'trang_thai', 
                            'ngay_tao'
                        ];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

    public function khachhang(){
        return $this->belongsTo(KhachHangModel::class, 'id_khach_hang');
    }

    public function tinh_ten() {
        return $this->belongsTo(TinhModel::class, 'tinh', 'province_id');
    }
    public function huyen_ten() {
        return $this->belongsTo(HuyenModel::class, 'huyen', 'district_id');
    }
    public function phuong_ten() {
        return $this->belongsTo(PhuongModel::class, 'phuong', 'ward_code');
    }

    public function getKetHopDiaChiAttribute()
    {
        /*return $this->dia_chi . ', ' .
            $this->tinh_ten->province_name . ', ' .
            $this->huyen_ten->district_name . ', ' .
            $this->phuong_ten->ward_name;*/
        return $this->dia_chi . ', ' .
            $this->phuong_ten->ward_name . ', ' .
            $this->huyen_ten->district_name . ', ' .
            $this->tinh_ten->province_name;    
    }
}