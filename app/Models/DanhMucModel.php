<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\DateFormat;

class DanhMucModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'danh_muc';
    protected $fillable = ['ten_danh_muc', 'trang_thai', 'ngay_tao'];

    // Nếu bỏ timestamps trong migration:
    public $timestamps = false;
}