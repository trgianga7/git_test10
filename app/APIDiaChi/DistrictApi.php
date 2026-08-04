<?php

namespace App\APIDiaChi;

/*class DistrictApi extends GhnClient
{
    public function byProvince(int $provinceId)
    {
        return $this->request('GET', "/master-data/district?province_id={$provinceId}");

    }

    public function findNameById(int $districtId)
    {
        foreach ($this->byProvince() as $d) {
            if ($d['DistrictID'] == $districtId) {
                return $d['DistrictName'];
            }
        }
        return null;
    
    }

}*/
class DistrictApi extends GhnClient
{
    public function byProvince(int $provinceId): array
    {
        $res = $this->request('GET', "/master-data/district?province_id={$provinceId}");
        return $res['data'] ?? []; // chỉ lấy phần data
    }

    public function findNameById(int $districtId, int $provinceId): ?string
    {
        foreach ($this->byProvince($provinceId) as $d) {
            if (isset($d['DistrictID']) && (int)$d['DistrictID'] === $districtId) {
                return $d['DistrictName'] ?? null;
            }
        }
        return null;
    }
}



