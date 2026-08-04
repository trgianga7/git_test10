<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DateFormat;
use App\Models\DanhGiaModel;

class DinhKemDanhGiaModel extends Model
{
    use DateFormat;

    protected $table = 'dinh_kem_danh_gia';
    protected $fillable = ['id_danh_gia',
                            'dinh_kem'
                        ];

    public $timestamps = false;         

}