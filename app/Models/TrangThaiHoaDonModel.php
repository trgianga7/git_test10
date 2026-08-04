<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\DateFormat;

class TrangThaiHoaDonModel extends Authenticatable
{
    use DateFormat;

    protected $table = 'trang_thai_hoa_don';
    protected $fillable = ['trang_thai'];

    public $timestamps = false;


}