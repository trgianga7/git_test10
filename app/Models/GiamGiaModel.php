<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\DateFormat;

class GiamGiaModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'giam_gia';
    protected $fillable = [ 'ten_giam_gia',
                            'loai_giam_gia', 
                            'ma_giam_gia', 
                            'gia_tri', 
                            'so_luong', 
                            'ngay_bat_dau',
                            'ngay_het_han', 
                            'trang_thai', 
                            'ngay_tao'
                        ];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

}