<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnhModel extends Model
{
    protected $table = 'hinh_anh';
    protected $fillable = ['id_san_pham_chi_tiet', 
                            'anh', 
                        ];

    public $timestamps = false;

    public function sanPhamChiTiet()
    {
        return $this->belongsTo(SanPhamChiTietModel::class, 'id_san_pham_chi_tiet');
    }
}