<?php

namespace App\Models\APIModel;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\DateFormat;

//class PhuongModel extends Authenticatable 
class PhuongModel extends Model 
{
    use DateFormat;

    protected $table = 'phuong';
    protected $fillable = ['ward_code', 
                        'ward_name',
                        'district_id'
                    ];

    public $timestamps = false;
}