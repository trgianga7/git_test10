<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DateFormat;

class ChucNangModel extends Model 
{
    use DateFormat;

    protected $table = 'chuc_nang';
    protected $fillable = ['ten_chuc_nang',
                        'route', 
                        'trang_thai', 
                        'ngay_tao'];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;
}