<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DateFormat;
use App\Models\ChucNangModel;

class ChucVuModel extends Model
{
    use DateFormat;

    protected $table = 'chuc_vu';
    protected $fillable = ['ten_chuc_vu', 
                        'trang_thai', 
                        'ngay_tao'];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;

    public function chucNangs()
    {
        return $this->belongsToMany(
            ChucNangModel::class,
            'quyen_han',
            'id_chuc_vu',
            'id_chuc_nang'
        )->withPivot('trang_thai');
    }

}