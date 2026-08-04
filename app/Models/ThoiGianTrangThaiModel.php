<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\DateFormat;

class ThoiGianTrangThaiModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'thoi_gian_trang_thai';
    protected $fillable = ['id_hoa_don',
                            'ls_trang_thai',
                            'thoi_gian_trang_thai'
                        ];

    public $timestamps = false;

    public function hoaDon()
    {
        return $this->belongsTo(HoaDonModel::class, 'id_hoa_don');
    }                    

}