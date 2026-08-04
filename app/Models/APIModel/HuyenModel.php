<?php

namespace App\Models\APIModel;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DateFormat;

//class HuyenModel extends Authenticatable 
class HuyenModel extends Model
{
    use DateFormat;

    protected $table = 'huyen';
    protected $fillable = ['district_id', 
                        'district_name',
                        'province_id',]; 

    public $timestamps = false;
}