<?php

namespace App\Models\APIModel;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\DateFormat;

//class TinhModel extends Authenticatable
class TinhModel extends Model
{
    use DateFormat;

    protected $table = 'tinh';
    protected $fillable = ['province_id', 
                        'province_name',];

    public $timestamps = false;
}