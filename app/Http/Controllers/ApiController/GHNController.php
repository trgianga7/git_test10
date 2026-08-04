<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\APIDiaChi\ProvinceApi;
use App\APIDiaChi\DistrictApi;
use App\APIDiaChi\WardApi;

class GHNController extends Controller
{
    public function provinces(ProvinceApi $provinceApi)
    {
        return response()->json(
            $provinceApi->all()
        );
    }

    public function districts(DistrictApi $districtApi, $provinceId)
    {
        return response()->json(
            $districtApi->byProvince((int)$provinceId)
        );
    }

    public function wards(WardApi $wardApi, $districtId)
    {
        return response()->json(
            $wardApi->byDistrict((int)$districtId)
        );
    }
}
